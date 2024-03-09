<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessBuild;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Process\Pipe;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PHPUnit\Event\Exception;
use function React\Promise\all;

class UploadController extends Controller
{
    private function generateRandomId($length = 5)
    {
        $subset = '1234567890qwertyuioplkjhgfdsazxcvbnm';
        $randomId = '';
        for ($i = 0; $i < $length; $i++) {
            $randomId .= $subset[random_int(0, strlen($subset) - 1)];
        }
        return $randomId;
    }

    public function ls()
    {
        // storage_path is going to be '/storage/'.
        $result = Process::path(storage_path('dep'))->run('ls -la')->throw();
        return $result->output();
    }

    /**
     * Clone a git repository given in request.
     * @param string $git_url
     * @return string $id
     */
    public function clone(string $git_url)
    {
        $id = $this->generateRandomId();
        // Changed setting of the local driver on 'filesystem.php' from 'root' => storage_path('app') to 'root' => storage_path().
        if (!Storage::exists('dep/clone_dir')) {
            Storage::makeDirectory('dep/clone_dir');
        }
        $result = Process::path(storage_path('dep/clone_dir'))
            ->run('git clone ' . escapeshellarg($git_url) . ' ' . $id)
            ->throw();
        return $id;
    }


    /**
     * Move the folder from clone_dir to output directory in R2 storage.
     * @param string $id
     * @return string|true
     */
    public function move(string $id)
    {
        try {
            $source = "dep/clone_dir/$id";
            $allFiles = Storage::disk('local')->allFiles($source);

            if (!Storage::disk('r2')->exists("output/$id")) {
                Storage::disk('r2')->makeDirectory("output/$id");
            }

            foreach ($allFiles as $file) {
                $relativePath = Str::after($file, $source . '/');
                // Read the file content to upload to R2
                $fileContent = Storage::get($file);
                // Upload the file content under the specific directory by $id in R2.
                $destination = "output/$id/$relativePath";
                Log::info("Uploading to R2: " . $destination);
                Storage::disk('r2')->put($destination, $fileContent);
            }
        } catch (\Exception $e) {
            // Log the error or return it as a response
            Log::error($e->getMessage());
            return $e->getMessage();
        }
        return true;
    }

    /**
     * Call `clone` function and `move` function.
     * @param Request $request
     * @return string
     */
    public function upload(Request $request)
    {
        try {
            $id = $this->clone($request->input('git_url'));
            $this->move($id);
        } catch (\Exception $e) {
            // Log the error or return it as a response
            Log::error($e->getMessage());
            Redis::hSet("status", $id, "failed to upload");
            return $e->getMessage();
        }
        Redis::hSet("status", $id, "uploaded");
        ProcessBuild::dispatch($id)->delay(now()->addSeconds(4))->onQueue('build-queue');
        return $id;
    }


    public function deleteOutput()
    {
        $directory = 'output';
        // Get all files in the directory
        $allFiles = Storage::disk('r2')->allFiles($directory);
        // Loop through and delete each file
        foreach ($allFiles as $file) {
            Storage::disk('r2')->delete($file);
        }
        Storage::disk('r2')->deleteDirectory($directory);
    }

    public function deleteDist()
    {
        $directory = 'dist';
        // Get all files in the directory
        $allFiles = Storage::disk('r2')->allFiles($directory);
        // Loop through and delete each file
        foreach ($allFiles as $file) {
            Storage::disk('r2')->delete($file);
        }
        Storage::disk('r2')->deleteDirectory($directory);
    }

}
