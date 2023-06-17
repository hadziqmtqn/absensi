<?php

namespace App\Http\Middleware;

use App\Helpers\DTO;
use App\Models\OnlineApi;
use Closure;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $enkripsi = Config::get('apikey.enkripsi');
        $apiKey = Config::get('apikey.api_key');
        $apiDomain = Config::get('apikey.api_domain');

        $client = new Client();

        $onlineApi = OnlineApi::first();

        try {
            $response = $client->get($onlineApi->website . '/api/get-api-key?enkripsi=' . $enkripsi);
            $data = json_decode($response->getBody());

            $enkripsiApiKey = Hash::check($apiKey, $data->data->api_key);

            if ($enkripsiApiKey && $apiDomain == $data->data->domain) {
                return $next($request);
            }else{
                return DTO::ResponseDTO('API Not Found', null, 'Unauthorized', null, Response::HTTP_BAD_REQUEST);
            }
        }catch(GuzzleException $th){
            Log::error($th->getMessage());

            return DTO::ResponseDTO('Data Failed', null, 'Server Error', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}