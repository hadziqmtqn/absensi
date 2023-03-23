<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;

use App\Models\User;
use App\Models\Setting;
use App\Models\Karyawan;
use App\Models\OnlineApi;
use App\Models\Role;
use GuzzleHttp\Client;
use Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Log;

class KaryawanController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:karyawan-list', ['only' => ['index','show']]);
        $this->middleware('permission:karyawan-create', ['only' => ['create','store']]);
        $this->middleware('permission:karyawan-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:karyawan-delete', ['only' => ['destroy','deletePermanen']]);
    }

    public function index()
    {
        $title = 'Data Karyawan';
        $appName = Setting::first();
        $karyawanAll = Karyawan::where('role_id',2)->withTrashed()->count();
        $karyawanActive = Karyawan::where('role_id',2)->count();
        $karyawanTrashed = Karyawan::where('role_id',2)->onlyTrashed()->count();

        return view('dashboard.karyawan.index', compact('title','appName','karyawanAll','karyawanActive','karyawanTrashed'));
    }

    public function getJsonKaryawan(Request $request)
    {
        if ($request->ajax()) {
            $data = Karyawan::where('role_id',2);

            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
					if ($request->get('is_verifikasi') == '0' || $request->get('is_verifikasi') == '1') {
                        $instance->where('is_verifikasi', $request->get('is_verifikasi'));
                    }

                    if (!empty($request->get('search'))) {
                            $instance->where(function($w) use($request){
                            $search = $request->get('search');
                            $w->orWhere('users.name', 'LIKE', "%$search%")
							->orWhere('users.email', 'LIKE', "%$search%")
							->orWhere('users.short_name', 'LIKE', "%$search%")
							->orWhere('users.phone', 'LIKE', "%$search%")
							->orWhere('users.nik', 'LIKE', "%$search%")
							->orWhere('users.company_name', 'LIKE', "%$search%");
                        });
                    }
                })

                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? with(new Carbon($row->created_at))->isoFormat('DD MMMM YYYY') : '';
                })

                ->addColumn('updated_at', function ($row) {
                    return $row->updated_at ? with(new Carbon($row->updated_at))->isoFormat('DD MMMM YYYY') : '';
                })

                ->addColumn('action', function($row){
					$btn = '<a href="karyawan/'.$row->username.'" class="btn btn-primary" style="padding: 7px 10px">Detail</a>';
                    $btn = $btn.' <button type="button" href="karyawan/'.$row->id.'/destroy" class="btn btn-danger btn-hapus" style="padding: 7px 10px">Delete</button>';
                    return $btn;
                })

                ->addColumn('status_verifikasi', function($row){
                    if($row->is_verifikasi){
                        return '<span class="badge badge-success">Sudah Diverifikasi</span>';
                    }else{
                        return '<span class="badge badge-warning">Belum Diverifikasi</span>';
                    }
                })

                ->addColumn('photo', function($row){
                    if($row->photo){
                        return '<img src="'.asset($row->photo).'" style="width: 30px; border-radius: 50%;" alt="image">';
                    }else{
                        return '<img src="'.asset('theme/template/images/user.png').'" style="width: 30px; border-radius: 50%;" alt="image">';
                    }
                })

                ->rawColumns(['action','status_verifikasi','photo'])
                ->addIndexColumn()
                ->make(true);
        }

        return response()->json(true);
    }

    public function trashed()
    {
        $title = 'Data Karyawan Terhapus';
        $appName = Setting::first();
        $karyawanAll = Karyawan::where('role_id',2)->withTrashed()->count();
        $karyawanActive = Karyawan::where('role_id',2)->count();
        $karyawanTrashed = Karyawan::where('role_id',2)->onlyTrashed()->count();

        return view('dashboard.karyawan.trash', compact('title','appName','karyawanAll','karyawanActive','karyawanTrashed'));
    }

    public function getJsonKaryawanTrashed(Request $request)
    {
        if ($request->ajax()) {
            $data = Karyawan::where('role_id',2)->onlyTrashed();

            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
					if ($request->get('is_verifikasi') == '0' || $request->get('is_verifikasi') == '1') {
                        $instance->where('is_verifikasi', $request->get('is_verifikasi'));
                    }

                    if (!empty($request->get('search'))) {
                            $instance->where(function($w) use($request){
                            $search = $request->get('search');
                            $w->orWhere('users.name', 'LIKE', "%$search%")
							->orWhere('users.email', 'LIKE', "%$search%")
							->orWhere('users.short_name', 'LIKE', "%$search%")
							->orWhere('users.phone', 'LIKE', "%$search%")
							->orWhere('users.nik', 'LIKE', "%$search%")
							->orWhere('users.company_name', 'LIKE', "%$search%");
                        });
                    }
                })

                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? with(new Carbon($row->created_at))->isoFormat('DD MMMM YYYY') : '';
                })

                ->addColumn('updated_at', function ($row) {
                    return $row->updated_at ? with(new Carbon($row->updated_at))->isoFormat('DD MMMM YYYY') : '';
                })

                ->addColumn('deleted_at', function ($row) {
                    return $row->deleted_at ? with(new Carbon($row->deleted_at))->isoFormat('DD MMMM YYYY') : '';
                })

                ->addColumn('action', function($row){
					$btn = '<button type="button" href="'.$row->id.'/restore" class="btn btn-warning btn-restore" style="padding: 7px 10px">Restore</button>';
                    $btn = $btn.' <button type="button" href="/karyawan/'.$row->id.'/delete-permanen" class="btn btn-danger btn-hapus" style="padding: 7px 10px">Delete</button>';
                    return $btn;
                })

                ->addColumn('status_verifikasi', function($row){
                    if($row->is_verifikasi){
                        return '<span class="badge badge-success">Sudah Diverifikasi</span>';
                    }else{
                        return '<span class="badge badge-warning">Belum Diverifikasi</span>';
                    }
                })

                ->addColumn('photo', function($row){
                    if($row->photo){
                        return '<img src="'.asset($row->photo).'" style="width: 30px; border-radius: 50%;" alt="image">';
                    }else{
                        return '<img src="'.asset('theme/template/images/user.png').'" style="width: 30px; border-radius: 50%;" alt="image">';
                    }
                })

                ->rawColumns(['action','status_verifikasi','photo'])
                ->addIndexColumn()
                ->make(true);
        }

        return response()->json(true);
    }

    public function detail($username)
    {
        $title = 'Detail Karyawan';
        $appName = Setting::first();
        $profile = User::where('username',$username)->first();
        $listKaryawan = User::where('role_id',2)->get();

        return view('dashboard.karyawan.detail', compact('title','appName','profile','listKaryawan'));
    }

    public function update(Request $request, $id)
	{
        $user = User::findOrFail($id);

        $client = New Client();
        $onlineApi = OnlineApi::first();

        try {
            $validator = Validator::make($request->all(),[
                'name' => ['required'],
                'short_name' => ['nullable'],
                'nik' => ['nullable'],
                'phone' => ['required', 'unique:users,phone,' . $user->id . 'id'],
                'company_name' => ['nullable'],
                'photo' => ['file', 'mimes:jpg,jpeg,png', 'max:1024'],
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
                'short_name' => $request->short_name,
                'nik' => $request->nik,
                'phone' => $request->phone,
                'company_name' => $request->company_name,
                'photo' => $photo
            ];

            DB::transaction(function () use ($user, $client, $onlineApi, $data){
                $user->update($data);

                $updateKaryawanApi = [
                    'role_id' => $user->role_id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'short_name' => $user->short_name,
                    'nik' => $user->nik,
                    'phone' => $user->phone,
                    'company_name' => $user->company_name,
                ];

                $client->request('PUT', $onlineApi->website . '/api/user/' . $user->idapi . '/update', [
                    'json' => $updateKaryawanApi
                ]);

            });

            Alert::success('Sukses','Data Karyawan Berhasil Tersimpan');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            Alert::error('Oops', 'Data Error');
        }

		return redirect()->back();
	}

    public function update_password($username)
    {
        $title = 'Update Password Karyawan';
        $appName = Setting::first();
        $karyawan = User::where('username',$username)
        ->first();
        $listKaryawan = User::where('role_id',2)
        ->get();

        return view('dashboard.karyawan.update_password', compact('title','appName','karyawan','listKaryawan'));
    }

    public function password(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $client = New Client();
        $onlineApi = OnlineApi::first();

        try {
            $validator = Validator::make($request->all(),[
                'password' => ['required'],
                'confirm_password' => ['required', 'same:password'],
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $data = [
                'password' => Hash::make($request->password)
            ];

            DB::transaction(function () use ($user, $data, $client, $onlineApi, $request) {
                $user->update($data);

                $updatePasswordApi = [
                    'password' => $request->password,
                ];

                $client->request('PUT', $onlineApi->website . '/api/user/' . $user->idapi . '/update-password', [
                    'json' => $updatePasswordApi
                ]);
            });

            Alert::success('Sukses','Password berhasil diubah');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            Alert::error('Error', 'Data Error');
        }

        return redirect()->back();
    }

    public function verifikasi($id){
        try {
            $user = Karyawan::findOrFail($id);
            if($user->is_verifikasi == 1){
                $user->update([
                    'is_verifikasi' => 0,
                ]);

                $this->whatsapp($user->id);
            }else{
                $user->update([
                    'is_verifikasi' => 1
                ]);

                $this->whatsapp($user->id);
            }

            Alert::success('Sukses','Status Verifikasi Karyawan Berhasil di Update');
        } catch (\Exception $e) {
            Alert::error('Error',$e->getMessage());
        }

        return redirect()->back();
    }

    public function whatsapp($id){
        $aplikasi = Setting::first();
        $karyawan = Karyawan::find($id);
        $whatsappApi = DB::table('whatsapp_apis')->first();

        if($karyawan->is_verifikasi == 1){
            $userMessage = "Selamat ".$karyawan->name.", akun Anda berhasil *DIVERIFIKASI*, silahkan login menggunakan Email/No. HP dan Kata Sandi yang telah didaftarkan.\n\n\n";
            $userMessage .= "Tim Dev ".$aplikasi->application_name;
        }else{
            $userMessage = "Mohon maaf ".$karyawan->name.", akun Anda kami nonaktifkan. Info lebih lanjut hubungi Admin. Terima kasih\n\n\n";
            $userMessage .= "Tim Dev ".$aplikasi->application_name;
        }

        $curl = curl_init();
        $token = $whatsappApi->api_keys;

        $payload = [
            "data" => [
                [
                    'phone' => $karyawan->phone,
                    'message' => $userMessage,
                ],
            ]
        ];

        curl_setopt($curl, CURLOPT_HTTPHEADER,
            array(
                "Authorization: $token",
                "Content-Type: application/json"
            )
        );
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload) );
        curl_setopt($curl, CURLOPT_URL, $whatsappApi->domain."/api/v2/send-message");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($curl);
        curl_close($curl);

        // echo "<pre>";
        // print_r($result);
        return $result;
    }

    public function destroy($id)
    {
        $client = new Client();
        $onlineApi = OnlineApi::first();

        $user = Karyawan::findOrFail($id);

        try {
            DB::transaction(function() use ($client, $onlineApi, $user){
                $user->delete();

                $client->request('DELETE', $onlineApi->website . '/api/user/' . $user->idapi . '/delete');
            });

            Alert::success('Sukses','Data Karyawan berhasil dihapus');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            Alert::error('Error','Data Error');
        }
        return redirect()->back();
    }

    public function deletePermanen($id)
    {
        $client = new Client();
        $onlineApi = OnlineApi::first();

        $user = Karyawan::onlyTrashed()
            ->findOrFail($id);

        try {
            DB::transaction(function() use ($client, $onlineApi, $user){
                $user->forceDelete();
                
                $client->request('DELETE', $onlineApi->website . '/api/user/' . $user->idapi . '/delete-permanen');
                
                Alert::success('Sukses','Data Karyawan berhasil dihapus permanen');
            });
        } catch (\Exception $e) {
            Alert::error('Error',$e->getMessage());
        }
        return redirect()->back();
    }

    public function restore($id)
    {
        $client = new Client();
        $onlineApi = OnlineApi::first();

        $user = Karyawan::withTrashed()->findOrFail($id);

        try {
            DB::transaction(function() use ($client, $onlineApi, $user){
                if($user->trashed()){
                    $user->restore();
    
                    $client->request('POST', $onlineApi->website . '/api/user/' . $user->idapi . '/restore');
    
                    Alert::success('Sukses','Data Karyawan berhasil di restore');
                } else {
                    Alert::error('Opps','Data Karyawan tidak terhapus');
                }
            });
        } catch (\Exception $e) {
            Alert::error('Error',$e->getMessage());
        }

        return redirect()->back();
    }
}
