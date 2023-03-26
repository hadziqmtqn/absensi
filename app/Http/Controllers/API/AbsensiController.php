<?php

namespace App\Http\Controllers\API;

use App\Helpers\DTO;
use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AbsensiController extends Controller
{
    public function store(Request $request, $idapi)
    {
        $user = User::where('idapi', $idapi)
        ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'user_id' => ['required', 'exists:users,id'],
            'waktu_absen' => ['required'],
        ]);

        if ($validator->fails()) {
            return DTO::ResponseDTO('Gagal Absen', null, $validator->errors(), null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $data = [
                'user_id' => $user->id,
                'waktu_absen' => $request->waktu_absen,
            ];

            $absensi = Absensi::create($data);

        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return DTO::ResponseDTO('Gagal Absen',  null, 'Oops, error', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return DTO::ResponseDTO('Sukses Absen', null, null, $absensi, Response::HTTP_OK);
    }
}
