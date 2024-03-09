<?php


namespace App\Http\Controllers;

use App\Events\DeployStatusMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Event\Exception;

class StatusController extends Controller
{

    /**
     * When you want to check its status based on id, it can be used.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
   public function status(Request $request)
   {
       $id = $request->query('id');
       $status = Redis::hGet("status", $id);
       event(new DeployStatusMessage('deployed', $id));
       return response()->json(['status' => $status]);
   }
}
