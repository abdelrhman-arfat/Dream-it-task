<?php

namespace App\Triats;

use App\Models\Logs;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

trait LoggerTrait
{
    /**
     * @param string|null $endpoint اختياري، لو عايز تحدد endpoint معين
     */
    public function databaseLog($endpoint = null)
    {

        // Skip logging during tests
        if (app()->runningUnitTests()) {
            return;
        }

        try {
            $user = auth()->user() ?? null;
            $currentUrl = url()->full();
            Logs::create([
                'user_id'  => $user?->id,
                'endpoint' => $currentUrl,
                'method'   => request()->method(),
            ]);
        } catch (\Exception $e) {
        }
    }

    public function logger($message = "failed", $data = [] )
    {

        $data['user_id'] = auth()->user()->id ?? null;
        $data['method'] = Request::method();
        $data["endpoint"] = url()->full();
        $data["route_name"] = Route::currentRouteName();
        Log::info($message, $data);
    }
}
