<?php

namespace App\Http\Controllers;

use App\Events\RealTimeMessage;
use App\Services\DeploymentService;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DeployController extends Controller
{

    // test redis
    public function index()
    {
        Redis::hSet('h', 'hello3', 'hello world');
        // Retrieving data from the Redis cache
        $value = Redis::hGet('h', 'hello3');
        return $value;
    }

    /**
     * Move all files in 'output/{id}/' directory in R2, into 'dep/output/{id}'.
     * @param string $id
     * @return boolean
     */
    public function download(string $id)
    {

        try {
            $source = "output/$id";
            $filesToBeDownloaded = Storage::disk('r2')->allFiles($source);
            if (!Storage::exists("dep/output/$id")) {
                Storage::makeDirectory("dep/output/$id");
            }

            foreach ($filesToBeDownloaded as $file) {
                $relativePath = Str::after($file, $source . '/');
                // Read the file content to download.
                $fileContent = Storage::disk('r2')->get($file);
                $destination = "dep/output/$id/$relativePath";
                // Upload the file content under the specific directory local.
                Log::info("Downloading to local: " . $destination);
                Storage::put($destination, $fileContent);
            }
        } catch (\Exception $e) {
            // Log the error or return it as a response
            Log::error($e->getMessage());
            return $e->getMessage();
        }
        return true;
    }

    /**
     * Run 'npm install' and 'npm run build' in `dep/output/$id` local directory.
     * @param string $id
     * @return string
     */
    public function build(string $id)
    {
        $buildDir = storage_path('dep/output/' . $id);
        $result = Process::path($buildDir)
            ->run('npm install && npm run build')
            ->throw();
        return $result->output();
    }

    /**
     * Send back the built contents produced by 'npm run build' to R2 again.
     * @param string $id
     * @return string
     */
    public function moveToR2(string $id)
    {
        try {
            $source = "dep/output/$id/build";
            $allFiles = Storage::disk('local')->allFiles($source);

            if (!Storage::disk('r2')->exists("dist/$id")) {
                Storage::disk('r2')->makeDirectory("dist/$id");
            }
            foreach ($allFiles as $file) {
                $relativePath = Str::after($file, $source . '/');
                // Read the file content to upload to R2
                $fileContent = Storage::get($file);
                // Upload the file contents under `build` directory within the specific directory by $id into `dist/$id` directory in R2.
                $destination = "dist/$id/$relativePath";
                Log::info("Uploading to R2 after running build: " . $destination);
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
     * Call `download`, `build` and `moveToR2` function to build and upload React project.
     * I build this function to be called by app/Services/DeployService.php.
     * @param string $id
     * @return string|void
     */
    public function deploy(string $id)
    {
        try {
            $this->download($id);
            $this->build($id);
            $this->moveToR2($id);
            Log::info("Setting status to deployed for ID: $id");
            Redis::hSet("status", $id, "deployed");
        } catch (\Exception $e) {
            Log::error("Failed to deploy: " . $e->getMessage());
            Redis::hSet("status", $id, "failed to deploy");
            return $e->getMessage();
        }
    }

}
