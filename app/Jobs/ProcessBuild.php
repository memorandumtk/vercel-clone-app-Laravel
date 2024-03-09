<?php

namespace App\Jobs;

use App\Http\Controllers\DeployController;
use App\Services\DeployService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use App\Events\DeployStatusMessage;

class ProcessBuild implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * This function is a queue worker to execute deploy process.
     * When there is some queue in 'build-queue', this function will be triggered.
     * @param DeployService $deployService
     * @return void
     */
    public function handle(DeployService $deployService)
    {
        try {
            $deployService->deploy($this->id);
            Log::info("$this->id's ProcessBuild job has done and web sockets event will be dispatched.");
        } catch (\Exception $e){
            Log::error($e);
        }
    }
}
