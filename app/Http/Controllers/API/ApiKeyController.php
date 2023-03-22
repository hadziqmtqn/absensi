<?php

namespace App\Http\Controllers\API;

use App\Helpers\DTO;
use App\Http\Controllers\Controller;
use App\Models\ApiKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyController extends Controller
{
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
