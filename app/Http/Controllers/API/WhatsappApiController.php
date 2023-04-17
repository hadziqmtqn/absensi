<?php

namespace App\Http\Controllers\API;

use App\Helpers\DTO;
use App\Http\Controllers\Controller;
use App\Models\WhatsappApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class WhatsappApiController extends Controller
{
    public function update(Request $request, $id)
    {
        $whatsappApi = WhatsappApi::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'domain' => ['required'],
            'api_keys' => ['required'],
            'no_hp_penerima' => ['required']
        ]);

        if ($validator->fails()) {
            return DTO::ResponseDTO('Whatsapp API Gagal Tersimpan', null, $validator->errors(), null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $data = [
                'domain' => $request->domain,
                'api_keys' => $request->api_keys,
                'no_hp_penerima' => $request->no_hp_penerima,
            ];

            $whatsappApi->update($data);

        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return DTO::ResponseDTO('Whatsapp API Gagal Tersimpan',  null, 'Oops, error', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return DTO::ResponseDTO('Whatsapp API Berhasil Tersimpan', null, null, $whatsappApi, Response::HTTP_OK);
    }
}
