<?php

namespace App\Http\Controllers\API;

use App\Helpers\DTO;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'short_name' => ['required'],
            'phone' => ['required', 'unique:users,phone'],
            'company_name' => ['nullable'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required']
        ]);

        if ($validator->fails()) {
            return DTO::ResponseDTO('Gagal Tambah Karyawan', null, $validator->errors(), null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $data = [
                'name' => $request->name,
                'idapi' => $request->idapi,
                'username' => Str::slug($request->name),
                'short_name' => $request->short_name,
                'phone' => $request->phone,
                'company_name' => $request->company_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_verifikasi' => '1'
            ];

            $user = User::create($data);
            $user->assignRole('2');

        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return DTO::ResponseDTO('Gagal Tambah Karyawan',  null, 'Oops, error', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return DTO::ResponseDTO('Sukses Tambah Karyawan', null, null, $user, Response::HTTP_OK);
    }
    
    public function update(Request $request, $idapi)
    {
        $user = User::where('idapi', $idapi)
        ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'short_name' => ['required'],
            'phone' => ['required', 'unique:users,phone,' . $user->id . 'id'],
            'company_name' => ['nullable'],
            'nik' => ['nullable'],
            'email' => ['required', 'string', 'max:255', 'unique:users,email,' . $user->id . 'id']
        ]);

        if ($validator->fails()) {
            return DTO::ResponseDTO('Gagal Update Karyawan', null, $validator->errors(), null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $data = [
                'name' => $request->name,
                'idapi' => $user->idapi,
                'username' => $user->username,
                'short_name' => $request->short_name,
                'phone' => $request->phone,
                'company_name' => $request->company_name,
                'nik' => $request->nik,
                'email' => $request->email,
            ];

            $user->update($data);

        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return DTO::ResponseDTO('Gagal Update Karyawan',  null, 'Oops, error', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return DTO::ResponseDTO('Sukses Update Karyawan', null, null, $user, Response::HTTP_OK);
    }
    
    public function updatePassword(Request $request, $idapi)
    {
        $user = User::where('idapi', $idapi)
        ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'password' => ['required'],
        ]);

        if ($validator->fails()) {
            return DTO::ResponseDTO('Gagal Update Karyawan', null, $validator->errors(), null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $data = [
                'password' => Hash::make($request->password),
            ];

            $user->update($data);

        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return DTO::ResponseDTO('Gagal Update Karyawan',  null, 'Oops, error', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return DTO::ResponseDTO('Sukses Update Karyawan', null, null, $user, Response::HTTP_OK);
    }

    public function delete($idapi)
    {
        $user = User::where('idapi', $idapi)
        ->firstOrFail();

        if (is_null($user)) {
            return DTO::ResponseDTO('Gagal Menghapus Data Karyawan', null, 'Data Not Found', null, Response::HTTP_NOT_FOUND);
        }

        try {
            $user->delete();

        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return DTO::ResponseDTO('Gagal Menghapus Data Karyawan', null, 'Oops, error', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return DTO::ResponseDTO('Sukses Menghapus Data Karyawan', null, null, $user, Response::HTTP_OK);
    }

    public function restore($idapi)
    {
        $user = User::withTrashed()
        ->whereNotNull('deleted_at')
        ->where('idapi', $idapi)
        ->firstOrFail();

        if (is_null($user)) {
            return DTO::ResponseDTO('Gagal Mengembalikan Data Karyawan', null, 'Data Not Found', null, Response::HTTP_NOT_FOUND);
        }

        try {
            $user->restore();

        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return DTO::ResponseDTO('Gagal Mengembalikan Data Karyawan', null, 'Oops, error', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return DTO::ResponseDTO('Sukses Mengembalikan Data Karyawan', null, null, $user, Response::HTTP_OK);
    }

    public function deletePermanen($idapi)
    {
        $user = User::withTrashed()
        ->whereNotNull('deleted_at')
        ->where('idapi', $idapi)
        ->firstOrFail();

        if (is_null($user)) {
            return DTO::ResponseDTO('Gagal Menghapus Permanen Data Karyawan', null, 'Data Not Found', null, Response::HTTP_NOT_FOUND);
        }

        try {
            $user->forceDelete();

        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return DTO::ResponseDTO('Gagal Menghapus Permanen Data Karyawan', null, 'Oops, error', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return DTO::ResponseDTO('Sukses Menghapus Permanen Data Karyawan', null, null, $user, Response::HTTP_OK);
    }
}
