<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogRouteApiMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $log = [
            'URI' => $request->getUri(),
            'METHOD' => $request->getMethod(),
            'REQUEST_BODY' => $request->all(),
            'RESPONSE' => $response->getContent()
        ];

        try {
            $res = $response->getOriginalContent();

            if ($res['error'] != null) {
                $errorLog = Log::channel('error');
                $errorLog->error(json_encode($log));
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }

        Log::info(json_encode($log));

        return $response;
    }
}
