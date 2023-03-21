<?php

namespace App\Http\Middleware;

use App\Helpers\DTO;
use App\Models\ApiKey;
use App\Models\OnlineApi;
use Closure;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // try {
        //     $client = New Client();
        //     $response = $client->get('http://abc.com/api/cek-host');
        //     $hostHeader = $response->getHeaderLine('Host');
            
        //     $
        //     if ($hostHeader) {
        //         # code...
        //     }

        //     return $next($request);
        // } catch (RequestException $th) {
        //     return $th->getMessage();
        // } catch (\Throwable $th) {
        //     // tangani error lainnya
        //     dd('Terjadi kesalahan: ' . $th->getMessage());
        // }

        // Mendapatkan host dari request
        $requestedHost = $request->getHost();

        // Mencari API key berdasarkan domain yang diminta
        $apiKey = ApiKey::where('domain', $requestedHost)->first();
        dd($requestedHost);
        // Jika API key tidak ditemukan, maka tolak request
        if (!$apiKey) {
            return response()->json(['error' => 'Invalid API Key'], 403);
        }

        // Jika API key ditemukan, maka cek host dari referer
        $refererHost = parse_url($request->headers->get('referer'), PHP_URL_HOST);
        // dd($refererHost);
        // Jika host dari referer sama dengan host yang diperbolehkan, maka lanjutkan
        if ($refererHost === $apiKey->allowed_host) {
            return $next($request);
        } else {
            return response()->json(['error' => 'Invalid Referer'], 403);
        }
            
    }
}
