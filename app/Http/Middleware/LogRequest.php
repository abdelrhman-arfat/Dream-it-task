<?php

namespace App\Http\Middleware;

use App\Triats\LoggerTrait;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogRequest
{
    use LoggerTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!app()->runningUnitTests()) {
            try {
                if (!request()->is("api")) {

                    $this->databaseLog();
                }
            } catch (\Exception $e) {
                $this->logger("failed to log the request data in logs table", [
                    "message" => $e->getMessage(),
                ]);
            }
        }
        return $next($request);
    }
}
