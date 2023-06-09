<?php

namespace App\Http\Controllers\API;

use App\Helpers\DTO;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class SettingController extends Controller
{
    public function update(Request $request, $id)
    {
        $setting = Setting::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'application_name' => ['required'],
            'description' => ['nullable'],
            'email' => ['required'],
            'no_hp' => ['nullable'],
            'awal_absensi' => ['required'],
            'akhir_absensi' => ['required']
        ]);

        if ($validator->fails()) {
            return DTO::ResponseDTO('Setting Gagal Tersimpan', null, $validator->errors(), null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $data = [
                'application_name' => $request->application_name,
                'description' => $request->description,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'awal_absensi' => $request->awal_absensi,
                'akhir_absensi' => $request->akhir_absensi,
            ];

            $setting->update($data);

        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return DTO::ResponseDTO('Setting Gagal Tersimpan',  null, 'Oops, error', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return DTO::ResponseDTO('Setting Berhasil Tersimpan', null, null, $setting, Response::HTTP_OK);
    }
}
