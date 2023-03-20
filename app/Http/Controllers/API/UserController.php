<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\OnlineApi;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $client = new Client();
        $onlineApi = OnlineApi::first();
        $response = $client->get($onlineApi->website . '/api/list-user');

        $users = json_decode($response->getBody()->getContents());

        return $users;
    }
}
