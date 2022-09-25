<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Setting;
use RealRashid\SweetAlert\Facades\Alert;

class RegisterController extends Controller
{
    public function index()
    {
        $title = 'Registrasi Absensi Karyawan';
        $appName = Setting::first();

        return view('register', compact('title','appName'));
    }

    public function store(Request $request){
        $this->validate($request,[
            'name' => 'required|min:5',
            'short_name' => 'required',
            'nik',
            'phone' => 'required|unique:users',
            'company_name',
            'email' => 'email|unique:users',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password',
        ]);

        $data['role_id'] = 2;
        $data['name'] = $request->input('name');
        $data['username'] = rand();
        $data['short_name'] = $request->input('short_name');
        $data['nik'] = $request->input('nik');
        $data['phone'] = $request->input('phone');
        $data['company_name'] = $request->input('company_name');
        $data['email'] = $request->input('email');
        $data['password'] = bcrypt($request->input('password'));
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');

        $user = User::create($data);
        $user->assignRole('2');
        
        $this->whatsapp($user->id);
        return redirect()->route('login')->with(['success' => 'Sukses! Silahkan Login menggunakan Nomor HP/Email dan Kata Sandi']);
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
