<?php
namespace App\Services;

use App\Events\DeployStatusMessage;
use App\Jobs\ProcessBuild;
use Illuminate\Http\Request;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\DeployController;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Redis;

class DeployService
{
    protected DeployController $deployController;

    public function __construct(DeployController $deployController)
    {
        $this->deployController = $deployController;
    }

    public function deploy(string $id)
    {
        $this->deployController->deploy($id);
        event(new DeployStatusMessage('deployed', $id));
        return Redis::hGet("status", $id);
    }
}
