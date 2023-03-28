<?php

namespace App\Http\Controllers\API;

use App\Helpers\DTO;
use App\Http\Controllers\Controller;
use App\Models\PersonalAccessToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function home()
    {
        try {
            PersonalAccessToken::where('expires_at', '<', now())->delete();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return DTO::ResponseDTO('Token Error', null, 'Token Error', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return DTO::ResponseDTO('Need Login', null, 'Token Expired', null, Response::HTTP_UNAUTHORIZED);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:mysql.users'],
            'password' => ['required', 'string', 'min:8']
        ]);

        if ($validator->fails()) {
            return DTO::ResponseDTO('Register Failed', null, $validator->errors(), null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
            $token = $user->createToken('auth_token')->plainTextToken;
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            Log::error($th->getMessage());
            return DTO::ResponseDTO('Register Failed',  null, 'Oops, error', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return DTO::ResponseDTO('Register Succesfully', $token, null, $user, Response::HTTP_OK);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8']
        ]);

        if ($validator->fails()) {
            return DTO::ResponseDTO('Login Failed', null, $validator->errors(), null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            if (!Auth::attempt($request->only('email', 'password'))) {
                return DTO::ResponseDTO('Login Failed', null, 'Email or Password does not match', null, Response::HTTP_BAD_REQUEST);
            }

            $user = User::where('email', $request['email'])->firstOrFail();

            $token = $user->createToken('auth_token', ['*'], now()->addRealHours(8))->plainTextToken;
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return DTO::ResponseDTO('Login Failed', null, 'Oops, error', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return DTO::ResponseDTO('Login Succesfully', $token, null, [
            'id' => $user->id,
            'name' => $user->name,
        ], Response::HTTP_OK);
    }

    public function logout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => ['required', 'integer']
        ]);

        if ($validator->fails()) {
            return DTO::ResponseDTO('Logout Failed', null, $validator->errors(), null, Response::HTTP_BAD_REQUEST);
        }

        try {
            $request->user()->currentAccessToken()->delete();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return DTO::ResponseDTO('Logout Failed', null, 'Oops, error', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return DTO::ResponseDTO('Logout Sucesfully', null, null, 'success', Response::HTTP_OK);
    }
}
