<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RequestController extends Controller
{

    /**
     * Display the form to enter a github repository link to be needed to be deployed.
     *
     */
    public function home()
    {
        return view('home');
    }


    /**
     * Move all files in 'output/{id}/' directory in R2, into 'dep/output/{id}'.
     * @param string $id
     * @param string $file
     * @return string|null
     */
    public function download(string $id, string $file)
    {
        try {
            $source = "dist/$id/$file";
            $fileContent = Storage::disk('r2')->get($source);
            Log::info("Downloading to local after the request: " . $source);
            return $fileContent;
        } catch (\Exception $e) {
            // Log the error or return it as a response
            Log::error($e->getMessage());
            return $e->getMessage();
        }
    }

    /**
     * Return the content based on the requested file content, downloading from `/dist/$id/` directory in R2.
     * @param Request $request
     * @param $id
     * @param $filePath
     * @return \Illuminate\Http\Response
     */
    public function request(Request $request, $id, $filePath)
    {
        Log::info("Accessed to the URL including $id as a subdomain of it.");
        // $id come from subdomain, $filepath comes from path.
        // Assuming $id and $file are correctly extracted from the request or hostname
        $fileContent = $this->download($id, $filePath);

        // Determine the content type based on the file extension
        $contentType = 'text/plain'; // Default content type
        if (str_ends_with($filePath, '.html')) {
            $contentType = 'text/html';
        } elseif (str_ends_with($filePath, '.css')) {
            $contentType = 'text/css';
        } elseif (str_ends_with($filePath, '.js')) {
            $contentType = 'application/javascript';
        } elseif (str_ends_with($filePath, '.png')) {
            $contentType = 'image/png';
        } elseif (str_ends_with($filePath, '.svg')) {
            $contentType = 'image/svg+xml';
        } elseif (str_ends_with($filePath, '.ico')) {
            $contentType = 'image/vnd.microsoft.icon';
        }

        // Return the response with the correct content type and make cache none.
        return response($fileContent)
            ->header('Content-Type', $contentType)
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');

    }
}
