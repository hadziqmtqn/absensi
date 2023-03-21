<?php

namespace App\Http\Controllers\API;

use App\Helpers\DTO;
use App\Http\Controllers\Controller;
use App\Models\OnlineApi;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'short_name' => ['required'],
            'phone' => ['required', 'unique:users,phone'],
            'company_name' => ['nullable'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required']
        ]);

        if ($validator->fails()) {
            return DTO::ResponseDTO('Create User Failed', null, $validator->errors(), null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $data = [
                'name' => $request->name,
                'short_name' => $request->short_name,
                'phone' => $request->phone,
                'company_name' => $request->company_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_verifikasi' => '1'
            ];

            $user = User::create($data);

        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return DTO::ResponseDTO('Create User Failed',  null, 'Oops, error', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return DTO::ResponseDTO('Create User Succesfully', null, null, $user, Response::HTTP_OK);
    }
}
