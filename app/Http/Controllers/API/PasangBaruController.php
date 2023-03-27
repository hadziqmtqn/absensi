<?php

namespace App\Http\Controllers\API;

use App\Helpers\DTO;
use App\Http\Controllers\Controller;
use App\Models\DataPasangBaru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class PasangBaruController extends Controller
{
    public function store(Request $request)
    {
        Validator::extend('without_spaces', function($attr, $value){
            return preg_match('/^\S*$/u', $value);
        });

        $validator = Validator::make($request->all(), [
            'pasang_baru_api' => ['required'],
            'kode' => ['required','unique:data_pasang_barus', 'without_spaces'],
            'inet' => ['required','unique:data_pasang_barus'],
            'nama_pelanggan' => ['required'],
            'no_hp' => ['required'],
            'alamat' => ['required'],
            'acuan_lokasi' => ['required'],
        ],
        [
            'kode.without_spaces' => 'Kode Harus Tanpa Spasi.'
        ]);

        if ($validator->fails()) {
            return DTO::ResponseDTO('Data Pasang Baru Gagal Tersimpan', null, $validator->errors(), null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $data = [
                'pasang_baru_api' => $request->pasang_baru_api,
                'inet' => $request->inet,
                'kode' => $request->kode,
                'nama_pelanggan' => $request->nama_pelanggan,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'acuan_lokasi' => $request->acuan_lokasi,
            ];

            $dataPasangBaru = DataPasangBaru::create($data);

        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return DTO::ResponseDTO('Data Pasang Baru Gagal Tersimpan',  null, 'Oops, error', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return DTO::ResponseDTO('Data Pasang Baru Berhasil Tersimpan', null, null, $dataPasangBaru, Response::HTTP_OK);
    }
    
    public function update(Request $request, $pasangBaruApi)
    {
        $dataPasangBaru = DataPasangBaru::pasangBaruApi($pasangBaruApi)
        ->firstOrFail();

        Validator::extend('without_spaces', function($attr, $value){
            return preg_match('/^\S*$/u', $value);
        });

        $validator = Validator::make($request->all(), [
            'kode' => ['required','unique:data_pasang_barus,kode,' . $dataPasangBaru->id . 'id', 'without_spaces'],
            'inet' => ['required','unique:data_pasang_barus,inet,' . $dataPasangBaru->id . 'id'],
            'nama_pelanggan' => ['required'],
            'no_hp' => ['required'],
            'alamat' => ['required'],
            'acuan_lokasi' => ['required'],
        ],
        [
            'kode.without_spaces' => 'Kode Harus Tanpa Spasi.'
        ]);

        if ($validator->fails()) {
            return DTO::ResponseDTO('Data Pasang Baru Gagal Tersimpan', null, $validator->errors(), null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $data = [
                'pasang_baru_api' => $dataPasangBaru->pasang_baru_api,
                'inet' => $request->inet,
                'kode' => $request->kode,
                'nama_pelanggan' => $request->nama_pelanggan,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'acuan_lokasi' => $request->acuan_lokasi,
            ];

            $dataPasangBaru->update($data);

        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return DTO::ResponseDTO('Data Pasang Baru Gagal Tersimpan',  null, 'Oops, error', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return DTO::ResponseDTO('Data Pasang Baru Berhasil Tersimpan', null, null, $dataPasangBaru, Response::HTTP_OK);
    }

    public function updateStatus(Request $request, $pasangBaruApi)
    {
        $dataPasangBaru = DataPasangBaru::pasangBaruApi($pasangBaruApi)
        ->firstOrFail();

        try {
            $data = [
                'status' => $request->status,
            ];

            $dataPasangBaru->update($data);

        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return DTO::ResponseDTO('Gagal Ubah Data Pasang Baru',  null, 'Oops, error', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return DTO::ResponseDTO('Data Pasang Baru Berhasil Tersimpan', null, null, $dataPasangBaru, Response::HTTP_OK);
    }

    public function delete($pasangBaruApi)
    {
        $dataPasangBaru = DataPasangBaru::pasangBaruApi($pasangBaruApi)
        ->firstOrFail();

        if (is_null($dataPasangBaru)) {
            return DTO::ResponseDTO('Data Pasang Baru Gagal Terhapus', null, 'Data Not Found', null, Response::HTTP_NOT_FOUND);
        }

        try {
            $dataPasangBaru->delete();

        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return DTO::ResponseDTO('Data Pasang Baru Gagal Terhapus', null, 'Oops, error', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return DTO::ResponseDTO('Data Pasang Baru Berhasil Terhapus', null, null, null, Response::HTTP_OK);
    }
}
