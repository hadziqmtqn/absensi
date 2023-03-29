<?php

namespace App\Http\Controllers\API;

use App\Helpers\DTO;
use App\Http\Controllers\Controller;
use App\Models\DataJob;
use App\Models\DataPasangBaru;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DataJobController extends Controller
{
    public function store($idapi, $pasangBaruApi)
    {
        $user = User::idApi($idapi)
        ->firstOrFail();

        $dataPasangBaru = DataPasangBaru::pasangBaruApi($pasangBaruApi)
        ->firstOrFail();

        try {
            $data = [
                'user_id' => $user->id,
                'kode_pasang_baru' => $dataPasangBaru->id,
            ];

            $dataJob = DataJob::create($data);

        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return DTO::ResponseDTO('Data Job Error',  null, 'Oops, error', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return DTO::ResponseDTO('Sukses Tambah Job Baru', null, null, $dataJob, Response::HTTP_OK);
    }
    
    public function update($idapi, $pasangBaruApi)
    {
        $user = User::idApi($idapi)
        ->firstOrFail();
        
        $dataPasangBaru = DataPasangBaru::where('pasang_baru_api', $pasangBaruApi)
        ->firstOrFail();

        try {
            $data = [
                'user_id' => $user->id,
                'kode_pasang_baru' => $dataPasangBaru->id,
            ];

            $dataJob = DataJob::where('kode_pasang_baru', $dataPasangBaru->id)
            ->update($data);

        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return DTO::ResponseDTO('Data Job Error',  null, 'Oops, error', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return DTO::ResponseDTO('Sukses Tambah Job Baru', null, null, $dataJob, Response::HTTP_OK);
    }

    public function delete($jobApi)
    {
        $dataJob = DataJob::jobApi($jobApi)
        ->firstOrFail();

        try {
            $dataJob->delete();

        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return DTO::ResponseDTO('Data Job Gagal Terhapus', null, 'Oops, error', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return DTO::ResponseDTO('Data Job Berhasil Terhapus', null, null, null, Response::HTTP_OK);
    }
}
