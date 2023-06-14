<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\OnlineApi;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Setting;
use App\Models\Role;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:profile-list', ['only' => ['index','update']]);
         $this->middleware('permission:profile-password', ['only' => ['update_password','password']]);
    }

    public function index()
    {
        $idUser = Auth::user()->id;
        $title = 'Profile Setting';
        $appName = Setting::first();
        $profile = User::where('id', $idUser)
        ->first();

        return view('dashboard.profile.index', compact('title','appName','profile'));
    }

    public function update(Request $request, $id)
	{
        $user = User::findOrFail($id);

        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required'],
                'photo' => ['nullable','file','mimes:jpg,jpeg,png','max:1024'],
                'email' => ['required', 'unique:users,email,' . $user->id . 'id'],
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $file = $request->file('photo');
            if($file){
                $nama_file = rand().'-'. $file->getClientOriginalName();
                $file->move('assets',$nama_file);
                $photo = 'assets/' .$nama_file;
            }else {
                $photo = $user->photo;
            }

            $data = [
                'role_id' => $user->role_id,
                'name' => $request->name,
                'email' => $request->email,
                'photo' => $photo
            ];

            $user->update($data);

            Alert::success('Sukses','Profile berhasil diupdate');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            Alert::error('Oops','Data Error');
        }

		return redirect()->back();
	}

    public function update_password()
    {
        $idUser = Auth::user()->id;
        $title = 'Update Password';
        $appName = Setting::first();
        $profile = User::where('id',$idUser)->first();
        $listRole = Role::get();

        return view('dashboard.profile.update_password', compact('title','appName','profile','listRole'));
    }

    public function password(Request $request,$id)
    {
        try {
            $password = $request->password;
            $confirm_password = $request->confirm_password;

            if($password != $confirm_password){
                Alert::error('Error','Password harus sama');
            }else{
                User::where('id',$id)->update([
                    'password' => bcrypt($password)
                ]);

                Alert::success('Sukses','Password berhasil diupdate');
            }
        } catch (\Exception $e) {
            Alert::error('Error',$e->getMessage());
        }

        return redirect()->back();
    }
}
