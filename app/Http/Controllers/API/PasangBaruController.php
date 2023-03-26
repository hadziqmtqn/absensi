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
            'kode' => ['required','unique:data_pasang_barus', 'without_spaces'],
            'inet' => ['required','unique:data_pasang_barus'],
            'nama_pelanggan' => ['required'],
            'no_hp' => ['required'],
            'alamat' => ['required'],
            'acuan_lokasi' => ['required'],
        ]);

        if ($validator->fails()) {
            return DTO::ResponseDTO('Gagal Absen', null, $validator->errors(), null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $data = [
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

            return DTO::ResponseDTO('Gagal Absen',  null, 'Oops, error', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return DTO::ResponseDTO('Sukses Absen', null, null, $dataPasangBaru, Response::HTTP_OK);
    }
}
