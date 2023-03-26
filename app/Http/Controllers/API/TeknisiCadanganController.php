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
    public function delete($idapi)
    {
        $user = User::idApi($idapi)
        ->firstOrFail();

        if (is_null($user)) {
            return DTO::ResponseDTO('Gagal Menghapus Teknisi Cadangan', null, 'Data Not Found', null, Response::HTTP_NOT_FOUND);
        }

        try {
            $teknisiCadangan = TeknisiCadangan::where('user_id', $user->id)
            ->delete();

        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return DTO::ResponseDTO('Gagal Menghapus Teknisi Cadangan', null, 'Oops, error', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return DTO::ResponseDTO('Sukses Menghapus Teknisi Cadangan', null, null, $teknisiCadangan, Response::HTTP_OK);
    }
}
