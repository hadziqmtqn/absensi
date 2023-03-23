<?php

namespace App\Http\Controllers\API;

use App\Helpers\DTO;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends Controller
{
    public function update(Request $request, $idapi)
    {
        $user = User::where('idapi', $idapi)
        ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255', 'unique:users,email,' . $user->id . 'id'],
            'photo' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:1024']
        ]);

        if ($validator->fails()) {
            return DTO::ResponseDTO('Update User Failed', null, $validator->errors(), null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $file = $request->file('photo');
        if($file){
            $nama_file = rand().'-'. $file->getClientOriginalName();
            $file->move('assets',$nama_file);
            $photo = 'assets/' .$nama_file;
        }else {
            $photo = $user->photo;
        }

        try {
            $data = [
                'idapi' => $user->idapi,
                'name' => $request->name,
                'email' => $request->email,
                'photo' => $photo
            ];

            $user->update($data);

        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return DTO::ResponseDTO('Update User Failed',  null, 'Oops, error', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return DTO::ResponseDTO('Update User Succesfully', null, null, $user, Response::HTTP_OK);
    }
}
