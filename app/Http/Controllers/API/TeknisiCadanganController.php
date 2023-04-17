<?php

namespace App\Http\Controllers\API;

use App\Helpers\DTO;
use App\Http\Controllers\Controller;
use App\Models\TeknisiCadangan;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TeknisiCadanganController extends Controller
{
    public function store($idapi)
    {
        $user = User::idApi($idapi)
        ->firstOrFail();

        try {
            $data = [
                'user_id' => $user->id,
            ];

            $dataJob = TeknisiCadangan::create($data);

        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return DTO::ResponseDTO('Teknisi Cadangan Gagal Tersimpan',  null, 'Oops, error', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return DTO::ResponseDTO('Teknisi Cadangan Berhasil Tersimpan', null, null, $dataJob, Response::HTTP_OK);
    }

    public function delete($idapi)
    {
        $teknisiCadangan = TeknisiCadangan::with('user')
        ->whereHas('user', function($query) use ($idapi){
            $query->idApi($idapi);
        })
        ->firstOrFail();

        try {
            $teknisiCadangan->delete();

        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return DTO::ResponseDTO('Gagal Menghapus Teknisi Cadangan', null, 'Oops, error', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return DTO::ResponseDTO('Sukses Menghapus Teknisi Cadangan', null, null, $teknisiCadangan, Response::HTTP_OK);
    }
}
