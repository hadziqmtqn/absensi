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
        $access_token = Auth::user()->access_token();
        dd($access_token);
        die();
        $client = new Client();
        $response = $client->get('http://localhost:8001/api/list-user', [
            'headers' => [
                'Authorization' => 'Bearer '.$access_token,
//                'Authorization' => 'Bearer cFlzhKGEYDSIYoYhlCoPE8Gc2BO3gK0QlXoATrHM',
                'Accept' => 'application/json',
            ]
        ]);

        $users = json_decode($response->getBody()->getContents());

        // Memasukkan data user ke dalam database di Laravel B
//        foreach ($users as $user) {
//            DB::table('users')->insert([
//                'name' => $user->name,
//                'email' => $user->email,
//                'role_id' => 2,
//                'password' => Hash::make('12345678'),
//            ]);
//        }

        return $users;
    }
}
