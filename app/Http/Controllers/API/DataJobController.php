<?php

namespace App\Http\Controllers\API;

use App\Helpers\DTO;
use App\Http\Controllers\Controller;
use App\Models\DataJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class DataJobController extends Controller
{
    public function store(Request $request, $idapi)
    {
        $user = User::idApi($idapi)
        ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'user_id' => ['required'],
            'kode_pasang_baru' => ['required']
        ]);

        if ($validator->fails()) {
            return DTO::ResponseDTO('Data Job Error', null, $validator->errors(), null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $data = [
                'user_id' => $user->id,
                'kode_pasang_baru' => $request->kode_pasang_baru,
            ];

            $dataJob = DataJob::create($data);

        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return DTO::ResponseDTO('Data Job Error',  null, 'Oops, error', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return DTO::ResponseDTO('Sukses Absen', null, null, $dataJob, Response::HTTP_OK);
    }
}
