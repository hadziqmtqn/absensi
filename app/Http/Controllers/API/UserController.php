<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $client = new Client();
        $response = $client->get('http://localhost:8001/api/list-user');

        $users = json_decode($response->getBody()->getContents());

        return $users;
    }
}
