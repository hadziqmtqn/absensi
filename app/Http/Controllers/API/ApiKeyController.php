<?php

namespace App\Http\Controllers\API;

use App\Helpers\DTO;
use App\Http\Controllers\Controller;
use App\Models\ApiKey;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyController extends Controller
{
    use AuthenticatesUsers;

    protected $maxAttempts = 3;
    protected $decayMinutes = 2;

    // public function apiKeyLogin(Request $request)
    // {
    //     $this->validate($request, [
    //         'enkripsi' => ['required'],
    //         'password' => ['required']
    //     ]);

    //     if (auth()->guard('apikeys')->attempt($request->only('enkripsi', 'api_key'))) {
    //         $request->session()->regenerate();
    //         $this->clearLoginAttempts($request);

    //         return redirect()->intended();
    //     } else {
    //         $this->incrementLoginAttempts($request);

    //         return redirect()
    //             ->back()
    //             ->withInput()
    //             ->withErrors(["Incorrect user login details!"]);
    //     }
    // }

    public function apiKeyLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'enkripsi' => ['required'],
            'api_key' => ['required']
        ]);

        if ($validator->fails()) {
            return DTO::ResponseDTO('Access Failed', null, $validator->errors(), null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            if (!Auth::guard('api_key')->attempt($request->only('enkripsi', 'api_key'))) {
                return DTO::ResponseDTO('Access Failed', null, 'Enkripsi or API Key does not match', null, Response::HTTP_BAD_REQUEST);
            }

            $apiKey = ApiKey::where('enkripsi', $request['enkripsi'])->firstOrFail();

            $token = $apiKey->createToken('api_key_token', ['*'], now()->addRealHours(8))->plainTextToken;
        } catch (GuzzleException $th) {
            Log::error($th->getMessage());
            return DTO::ResponseDTO('Access Failed', null, 'Oops, error', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return DTO::ResponseDTO('Access Succesfully', $token, null, [
            'id' => $apiKey->id,
            'domain' => $apiKey->domain,
        ], Response::HTTP_OK);
    }

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'enkripsi' => 'required',
        ]);

        if ($validator->fails()) {
            return DTO::ResponseDTO('Check API Failed', null, $validator->errors(), null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $apiKey = ApiKey::where('enkripsi', $request->input('enkripsi'))
            ->first();

        if (!$apiKey) {
            return DTO::ResponseDTO('API not found',  null, 'Oops, error', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return DTO::ResponseDTO('API Key', null, null, $apiKey, Response::HTTP_OK);
    }
}
