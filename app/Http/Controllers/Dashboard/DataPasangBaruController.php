<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\DataJob;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\Datatables\Datatables;

use App\Models\Setting;
use App\Models\DataPasangBaru;
use App\Models\OnlineApi;
use App\Models\TeknisiCadangan;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DataPasangBaruController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:data-pasang-baru-list', ['only' => ['index','detail']]);
        $this->middleware('permission:data-pasang-baru-create', ['only' => ['create','store']]);
        $this->middleware('permission:data-pasang-baru-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:data-pasang-baru-delete', ['only' => ['destroy']]);
    }
    
    public function index()
    {
        $title = 'Data Pasang Baru';
        $appName = Setting::first();

        return view('dashboard.data_pasang_baru.index', compact('title','appName'));
    }

    public function getJsonPasangBaru(Request $request)
    {
        if ($request->ajax()) {
			$data = DataPasangBaru::orderBy('created_at','DESC');
            
            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
					if ($request->get('status') == '0' || $request->get('status') == '1' || $request->get('status') == '2' || $request->get('status') == '3') {
                        $instance->where('status', $request->get('status'));
                    }

                    if ($request->get('created_at') != null) {
                        $instance->whereDate('created_at', $request->created_at);
                    }

                    if (!empty($request->get('search'))) {
                            $instance->where(function($w) use($request){
                            $search = $request->get('search');
                            $w->orWhere('data_pasang_barus.kode', 'LIKE', "%$search%")
							->orWhere('data_pasang_barus.inet', 'LIKE', "%$search%")
							->orWhere('data_pasang_barus.nama_pelanggan', 'LIKE', "%$search%")
							->orWhere('data_pasang_barus.no_hp', 'LIKE', "%$search%")
							->orWhere('data_pasang_barus.alamat', 'LIKE', "%$search%")
							->orWhere('data_pasang_barus.acuan_lokasi', 'LIKE', "%$search%");
                        });
                    }
                })

                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? with(new Carbon($row->created_at))->isoFormat('lll') : '';
                })

                ->addColumn('updated_at', function ($row) {
                    return $row->updated_at ? with(new Carbon($row->updated_at))->isoFormat('lll') : '';
                })

                ->addColumn('action', function($row){
					$btn = '<a href="data-pasang-baru/'.$row->kode.'" class="btn btn-primary" style="padding: 7px 10px">Detail</a>';
                    $btn = $btn.' <a href="data-pasang-baru/edit/'.$row->kode.'" class="btn btn-warning" style="padding: 7px 10px">Edit</a>';
                    if (!$row->data_job) {
                        $btn = $btn.' <button type="button" href="data-pasang-baru/hapus/'.$row->id.'" class="btn btn-danger btn-hapus" style="padding: 7px 10px">Delete</button>';
                    }

                    return $btn;
                })

                ->addColumn('status', function($row){
                    if($row->status == 0){
                        return '<span class="badge badge-info">Waiting</span>';
                    }elseif($row->status == 1){
                        return '<span class="badge badge-primary">In Progress</span>';
                    }elseif($row->status == 2){
                        return '<span class="badge badge-warning">Pending</span>';
                    }elseif($row->status == 3){
                        return '<span class="badge badge-success">Success</span>';
                    }
                })

                ->addColumn('foto', function($row){
                    if(!empty($row->foto)){
                        return '<img src="'.asset($row->foto).'" style="width: 30px; border-radius: 50%;" alt="image">';
                    }
                })

                ->rawColumns(['action','status','foto'])
                ->addIndexColumn()
                ->make(true);
        }

        return response()->json(true);
    }

    public function store(Request $request)
	{
        $toDay = Carbon::now();
        $absensi = Absensi::with('user')
        ->whereHas('user', function($query) use ($toDay){
            $query->whereDoesntHave('dataJob', function($subQuery) use ($toDay){
                $subQuery->whereDate('created_at', $toDay);
            });
        })
        ->whereDate('created_at', $toDay)
        ->first();

        $teknisiCadangan = TeknisiCadangan::with('user')
        ->whereHas('user', function($query) use ($toDay){
            $query->whereHas('absensi', function($subQuery) use ($toDay){
                $subQuery->whereDate('created_at', $toDay);
            });
        })
        ->whereDate('created_at', $toDay)
        ->first();

        $client = New Client();
        $onlineApi = OnlineApi::first();

        Validator::extend('without_spaces', function($attr, $value){
            return preg_match('/^\S*$/u', $value);
        });
        
        try {
            $validator = Validator::make($request->all(),[
                'kode' => ['required','unique:data_pasang_barus', 'without_spaces'],
                'inet' => ['required','unique:data_pasang_barus'],
                'nama_pelanggan' => ['required'],
                'no_hp' => ['required'],
                'alamat' => ['required'],
                'acuan_lokasi' => ['required'],
                'foto' => ['nullable','file','mimes:jpg,jpeg,png','max:1024']
            ],
            [
                'kode.without_spaces' => 'Kode Harus Tanpa Spasi.'
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }
    
            $file = $request->file('foto');
            if($file){
                $nama_file = rand().'-'. $file->getClientOriginalName();
                $file->move('assets',$nama_file);
                $foto = 'assets/' .$nama_file;
            }else {
                $foto = null;
            }

            $data = [
                'pasang_baru_api' => rand(),
                'kode' => $request->kode,
                'inet' => $request->inet,
                'nama_pelanggan' => $request->nama_pelanggan,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'acuan_lokasi' => $request->acuan_lokasi,
                'foto' => $foto
            ];
            
            DB::transaction(function() use ($data, $absensi, $teknisiCadangan, $client, $onlineApi){
                $dataPasangBaru = DataPasangBaru::create($data);
                
                $dataPasangBaruApi = [
                    'pasang_baru_api' => $dataPasangBaru->pasang_baru_api,
                    'kode' => $dataPasangBaru->kode,
                    'inet' => $dataPasangBaru->inet,
                    'nama_pelanggan' => $dataPasangBaru->nama_pelanggan,
                    'no_hp' => $dataPasangBaru->no_hp,
                    'alamat' => $dataPasangBaru->alamat,
                    'acuan_lokasi' => $dataPasangBaru->acuan_lokasi,
                ];

                $client->request('POST', $onlineApi->website . '/api/data-pasang-baru', [
                    'json' => $dataPasangBaruApi
                ]);
                
                if ($absensi && !$teknisiCadangan) {
                    $createJobBaru = [
                        'user_id' => $absensi->user_id,
                        'kode_pasang_baru' => $dataPasangBaru->id
                    ];
                    
                    DataJob::create($createJobBaru);
                    
                    $client->request('POST', $onlineApi->website . '/api/data-job/' . $absensi->user->idapi . '/' . $dataPasangBaru->pasang_baru_api);
                }elseif (!$absensi && $teknisiCadangan) {
                    $createJobBaru = [
                        'user_id' => $teknisiCadangan->user_id,
                        'kode_pasang_baru' => $dataPasangBaru->id
                    ];
                    
                    DataJob::create($createJobBaru);
                    
                    $client->request('POST', $onlineApi->website . '/api/data-job/' . $teknisiCadangan->user->idapi . '/' . $dataPasangBaru->pasang_baru_api);
                    
                    $client->request('DELETE', $onlineApi->website . '/api/teknisi-cadangan/' . $teknisiCadangan->user->idapi . '/delete');

                    $teknisiCadangan->delete();
                }
            });
            
            Alert::success('Sukses','Data Pasang Baru berhasil disimpan');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            Alert::error('Oops', 'Data Error');
        }

		return redirect()->back();
	}

    public function detail($kode)
    {
        $title = 'Detail Pasang Baru';
        $appName = Setting::first();
        $data = DataPasangBaru::where('kode',$kode)->first();
        $listPasangBaru = DataPasangBaru::orderBy('created_at','DESC')->get();

        if($data->status == 0){
            $badge = 'badge-info';
            $status = 'Waiting';
        }elseif($data->status == 1){
            $badge = 'badge-primary';
            $status = 'In Progress';
        }elseif($data->status == 2){
            $badge = 'badge-warning';
            $status = 'Pending';
        }elseif($data->status == 3){
            $badge = 'badge-success';
            $status = 'Success';
        }

        return view('dashboard.data_pasang_baru.detail', compact('title','appName','data','listPasangBaru','badge','status'));
    }

    public function edit($kode)
    {
        $title = 'Edit Pasang Baru';
        $appName = Setting::first();
        $data = DataPasangBaru::where('kode',$kode)->first();
        $listPasangBaru = DataPasangBaru::orderBy('created_at','DESC')->get();

        return view('dashboard.data_pasang_baru.edit', compact('title','appName','data','listPasangBaru'));
    }

    public function update(Request $request, $id)
	{
        try {
            $request->validate([
                'inet' => 'required',
                'kode' => 'required',
                'nama_pelanggan' => 'required',
                'no_hp' => 'required',
                'alamat' => 'required',
                'acuan_lokasi' => 'required',
                'foto' => 'file|mimes:jpg,jpeg,png|max:1024'
            ]);
            
            $data['inet'] = $request->inet;
            $data['kode'] = $request->kode;
            $data['nama_pelanggan'] = $request->nama_pelanggan;
            $data['no_hp'] = $request->no_hp;
            $data['alamat'] = $request->alamat;
            $data['acuan_lokasi'] = $request->acuan_lokasi;
            // $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
    
            $file = $request->file('foto');
            if($file){
                $nama_file = rand().'-'. $file->getClientOriginalName();
                $file->move('assets',$nama_file);
                $data['foto'] = 'assets/' .$nama_file;
            }
    
            DataPasangBaru::where('id',$id)->update($data);
            Alert::success('Sukses','Data Pasang Baru berhasil diupdate');
            
            return redirect()->route('data-pasang-baru.index');
        } catch (\Throwable $th) {
            Alert::error('Error',$th->getMessage());
            
            return redirect()->back();
        }
	}

    public function delete($id)
    {
        $dataPasangBaru = DataPasangBaru::findOrFail($id);

        try {
            $dataPasangBaru->delete();

            Alert::success('Sukses','Data Pasang Baru berhasil dihapus');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            Alert::error('Oops', 'Data Error');
        }

        return redirect()->back();
    }
}
