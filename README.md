
# Vercel Clone App
*thinking of only react project*

> This applicatioin was inspired by Hkirat's vidoe below.
> https://www.youtube.com/watch?v=c8_tafixiAs&t=7138s  
> This is the code description.
> https://projects.100xdevs.com/tracks/ZSQI8YNE0iL6sT1hJpts/vercel-1

Since I was strongly impressed by the Hkirat's youtube clip above, I got to want to make it by using Laravel not just copying his project by Node.js.

This took loong time until to be done, though I learned a lot of new things and capability of Laravel. I will introduce some thing of those.

***[To see the video of this project](https://twitter.com/TK47781211/status/1766566920663249330)***


## What I did (especially different way from Hkirat's one)
Of course JS(typescript) and PHP though.

### 1. About Environment
I used sail to leverage the benefit of Laravel and I made the way the `web socket` server in the one of sail containers, which I will write later. About the det\ail how I could build a web socket server is written below Github repository link. Using sail and Laravel project structure I was able to do almost same thing within one Laravel project described by Hkirat's video. 
https://github.com/memorandumtk/sail-websockets

For the cloud storage, I was going to use `AWS S3` and tried it. However I was a little bit scared of exceeding the amount of free use, then I shifted to use `CloudFlare R2` which is compatible for S3.

I have to say that I used Windows 11 to edit my code and check the result on browser and WSL2 to have sail environment and use DNS server, `dnsmasq` and `systemd-resolved`. I've set up my DNS like [this my twitter post](https://twitter.com/TK47781211/status/1765285953533575494) to be able to detect subdomain to get id like below. But saying this, such this URL is used a request to get deployed React files After deployed.
```
Route::domain('{id}.vercel-local.com')->group(function () {
```

### 2. Upload - To upload folders and files cloned from github repository which comes from a request into R2.
I did this by using Laravel Process and Storage facade. I used the former to clone git repository entered to input form in the browser and the latter to move files (those content) between local storage and R2 storage. I changed the storage setting for it in `config/filesystems.php`. When the upload process has done, using Redis built on sail, it is going to be saved 'status' key, then dispatches `ProcessBuild` job into the queue.

***Example of R2 setting***
```text
'r2' => [
    'driver' => 's3',
    'key' => env('CLOUDFLARE_R2_ACCESS_KEY_ID'),
    'secret' => env('CLOUDFLARE_R2_SECRET_ACCESS_KEY'),
    'region' => env('CLOUDFLARE_R2_DEFAULT_REGION'),
    'bucket' => env('CLOUDFLARE_R2_BUCKET'),
    'endpoint' => env('CLOUDFLARE_R2_ENDPOINT'),
    'url' => env('CLOUDFLARE_R2_URL'),
    // Optional: To use path-style endpoint, which might be required for some configurations
    'use_path_style_endpoint' => env('CLOUDFLARE_R2_USE_PATH_STYLE_ENDPOINT', false),
    'throw' => false,
],
```

### 3. Deploy - To deploy the React project and trigger the web socket event after deployed. 
This part is the same as upload part for moving files and registering `status` in Redis. In addition, I use Process facade to run this command (npm install && npm run build) and fired new web sockets message to let the front code know deploy process has done. The code firing the message is in the `DeployService.php`
```text
event(new DeployStatusMessage('deployed', $id));
```

### 4. About Requests
###### Sending a request of github repository URL which will be cloned.
I made this flow within `resources/views/deploy/form-of-deploy.blade.php` and `resources/js/send-giturl-form.js`

###### After the deploy process has done, what I made are:
1. To have a request from front-end to get a content of React project. This logic is in `app/Http/Controllers/RequestController.php`.
2. To change the display of front page with making `status-of-deploy` visible after receiving the web sockets event saying 'deployed' and verifying the id is correct using session storage value of id. This logic is written in `resources/js/receive-status-ws.js`.
3. Getting the deployed URL, to be able to access the URL. The URL would be like:
```text
http://{id}.vercel-local.com/{file}
```
{file} part can be index.html(initially), js file, css file and other requested based on index.html. These file path handled by below code, which is in `web.php`.
```text
Route::domain('{id}.vercel-local.com')->group(function () {
    // Define other routes that should match the subdomain here
    Route::get('/{file}', [\App\Http\Controllers\RequestController::class, 'request'])
        ->where('file', '.*'); // The '.*' is a wildcard to match any file path
});
```
