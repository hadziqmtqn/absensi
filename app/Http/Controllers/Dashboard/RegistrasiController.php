<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class RegistrasiController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:registrasi-create', ['only' => ['index','store']]);
    }

    public function index()
    {
        $title = 'Registrasi Absensi Karyawan';
        $appName = Setting::first();

        return view('dashboard.registrasi.index', compact('title','appName'));
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),[
                'name' => 'required|min:5',
                'short_name' => 'required',
                'phone' => 'required|unique:users',
                'company_name',
                'email' => 'email|unique:users',
                'password' => 'required|min:8',
                'confirm_password' => 'required|same:password',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $data = [
                'role_id' => 2,
                'name' => $request->input('name'),
                'username' => rand(),
                'short_name' => $request->input('short_name'),
                'phone' => $request->input('phone'),
                'company_name' => $request->input('company_name'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
                'is_verifikasi' => '1'
            ];

            DB::transaction(function () use ($data){
                $user = User::create($data);
                $user->assignRole('2');

                $this->whatsapp($user->id);
            });

            Alert::success('Success','Registrasi Absen Karyawan Berhasil Tersimpan');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            Alert::error('Oops','Data Error');
        }

        return redirect()->back();
    }

    public function whatsapp($registrasi){
        $aplikasi = Setting::first();
        $newUser = User::find($registrasi);
        $whatsappApi = DB::table('whatsapp_apis')->first();

        // konfigurasi notifikasi wa untuk admin
        $adminMessage = "Hei. Admin *".$aplikasi->application_name."* ada registrasi absensi karyawan baru atas nama: \n\n";
        $adminMessage .= "*Nama* : ".$newUser->name."\n";
        $adminMessage .= "*No. HP* : ".$newUser->phone."\n";
        $adminMessage .= "*Dari PT.* : ".$newUser->company_name."\n";
        $adminMessage .= "*Tanggal Registrasi* : ".date('d M Y', strtotime($newUser->created_at))."\n\n";
        $adminMessage .= "Terima kasih";

        // konfigurasi notifikasi wa untuk karyawan
        $userMessage = "Selamat, registrasi absensi karyawan atas nama: \n\n";
        $userMessage .= "*Nama* : ".$newUser->name."\n";
        $userMessage .= "*No. HP* : ".$newUser->phone."\n";
        $userMessage .= "*Dari PT.* : ".$newUser->company_name."\n";
        $userMessage .= "*Tanggal Registrasi* : ".date('d M Y', strtotime($newUser->created_at))."\n\n";
        $userMessage .= "berhasil disimpan, silahkan tunggu konfirmasi verifikasi data dari kami.\n\n";
        $userMessage .= "Tim Dev ".$aplikasi->application_name;

        $curl = curl_init();
        $token = $whatsappApi->api_keys;

        $payload = [
            "data" => [
                [
                    'phone' => $newUser->phone,
                    'message' => $userMessage,
                ],
                [
                    'phone' => $whatsappApi->no_hp_penerima,
                    'message' => $adminMessage,
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
}
