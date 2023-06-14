<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\DataJob;
use Exception;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Setting;
use App\Models\DataPasangBaru;
use App\Models\TeknisiCadangan;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

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

    /**
     * @throws Exception
     */
    public function getJsonPasangBaru(Request $request)
    {
        if ($request->ajax()) {
			$data = DataPasangBaru::query()
                ->orderBy('created_at','DESC');

            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
					if ($request->get('status') == '0' || $request->get('status') == '1' || $request->get('status') == '2' || $request->get('status') == '3') {
                        $instance->where('status', $request->get('status'));
                    }

                    if ($request->get('created_at') != null) {
                        $instance->whereDate('created_at', $request->input('created_at'));
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
					$btn = '<a href="data-pasang-baru/'.$row->id.'/detail" class="btn btn-primary" style="padding: 7px 10px">Detail</a>';
                    $btn .= ' <a href="data-pasang-baru/edit/'.$row->id.'" class="btn btn-warning" style="padding: 7px 10px">Edit</a>';
                    if (!$row->data_job) {
                        $btn .= ' <button type="button" href="data-pasang-baru/hapus/'.$row->id.'" class="btn btn-danger btn-hapus" style="padding: 7px 10px">Delete</button>';
                    }

                    return $btn;
                })

                ->addColumn('status', function($row){
                    $badge = $row->status == 0 ? 'badge-info' : ($row->status == '1' ? 'badge-primary' : ($row->status == '2' ? 'badge-warning' : ($row->status == '3' ? 'badge-success' : '')));
                    $status = $row->status == 0 ? 'Waiting' : ($row->status == '1' ? 'In Progress' : ($row->status == '2' ? 'Pending' : ($row->status == '3' ? 'Success' : '')));

                    return '<span class="badge '.$badge.'">'.$status.'</span>';
                })

                ->addColumn('foto', function($row){
                    return is_null($row->foto) ? null : '<img src="'.asset($row->foto).'" style="width: 30px; border-radius: 50%;" alt="image">';
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
        ->absensiBerlaku()
        ->orderBy('created_at','ASC')
        ->first();

        $teknisiCadangan = TeknisiCadangan::with('user')
        ->whereHas('user', function($query) use ($toDay){
            $query->whereHas('absensi', function($subQuery) use ($toDay){
                $subQuery->whereDate('created_at', $toDay);
            });
        })
        ->whereDate('created_at', $toDay)
        ->orderBy('created_at','ASC')
        ->first();

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
                'kode' => $request->input('kode'),
                'inet' => $request->input('inet'),
                'nama_pelanggan' => $request->input('nama_pelanggan'),
                'no_hp' => $request->input('no_hp'),
                'alamat' => $request->input('alamat'),
                'acuan_lokasi' => $request->input('acuan_lokasi'),
                'foto' => $foto
            ];

            DB::transaction(function() use ($data, $absensi, $teknisiCadangan){
                $dataPasangBaru = DataPasangBaru::create($data);

                if ($absensi && !$teknisiCadangan || $absensi && $teknisiCadangan) {
                    DataJob::create([
                        'user_id' => $absensi->user_id,
                        'kode_pasang_baru' => $dataPasangBaru->id
                    ]);
                }elseif (!$absensi && $teknisiCadangan) {
                    DataJob::create([
                        'user_id' => $teknisiCadangan->user_id,
                        'kode_pasang_baru' => $dataPasangBaru->id
                    ]);

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

    public function detail($id)
    {
        $title = 'Detail Pasang Baru';
        $appName = Setting::first();
        $dataPasangBaru = DataPasangBaru::findOrFail($id);

        $badge = $dataPasangBaru->status == 0 ? 'badge-info' : ($dataPasangBaru->status == '1' ? 'badge-primary' : ($dataPasangBaru->status == '2' ? 'badge-warning' : ($dataPasangBaru->status == '3' ? 'badge-success' : '')));
        $status = $dataPasangBaru->status == 0 ? 'Waiting' : ($dataPasangBaru->status == '1' ? 'In Progress' : ($dataPasangBaru->status == '2' ? 'Pending' : ($dataPasangBaru->status == '3' ? 'Success' : '')));

        return view('dashboard.data_pasang_baru.detail', compact('title','appName','dataPasangBaru','badge','status'));
    }

    public function edit($id)
    {
        $title = 'Edit Pasang Baru';
        $appName = Setting::first();
        $dataPasangBaru = DataPasangBaru::findOrFail($id);

        return view('dashboard.data_pasang_baru.edit', compact('title','appName','dataPasangBaru'));
    }

    public function update(Request $request, $id)
	{
        $dataPasangBaru = DataPasangBaru::findOrFail($id);

        try {
            Validator::extend('without_spaces', function($attr, $value){
                return preg_match('/^\S*$/u', $value);
            });

            $validator = Validator::make($request->all(), [
                'kode' => ['required','unique:data_pasang_barus,kode,' . $id . 'id', 'without_spaces'],
                'inet' => ['required','unique:data_pasang_barus,inet,' . $id . 'id'],
                'nama_pelanggan' => ['required'],
                'no_hp' => ['required'],
                'alamat' => ['required'],
                'acuan_lokasi' => ['required'],
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
                $foto = $dataPasangBaru->foto;
            }

            $data = [
                'kode' => $request->input('kode'),
                'inet' => $request->input('inet'),
                'nama_pelanggan' => $request->input('nama_pelanggan'),
                'no_hp' => $request->input('no_hp'),
                'alamat' => $request->input('alamat'),
                'acuan_lokasi' => $request->input('acuan_lokasi'),
                'foto' => $foto
            ];

            $dataPasangBaru->update($data);

            Alert::success('Sukses','Data Pasang Baru berhasil diupdate');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            Alert::error('Oops', 'Data Error');
        }

        return redirect()->back();
	}

    public function updateStatus(Request $request, $id)
	{
        $dataPasangBaru = DataPasangBaru::findOrFail($id);

        $toDay = date('Y-m-d');
        // jika ada teknisi yang absen hari ini dan belum memiliki job
        $teknisiNonJob = User::with('absensi','dataJob')
        ->whereHas('absensi', function($query) use ($toDay){
            $query->whereDate('created_at', $toDay);
        })
        ->whereDoesntHave('dataJob', function($query) use ($toDay){
            $query->whereDate('created_at', $toDay);
        })
        ->first();
        // jika ada pasang baru dan belum dimiliki oleh teknisi
        $pasangBaruNonJob = DataPasangBaru::with('data_job')
        ->whereDoesntHave('data_job')
        ->first();

        $pasangBaruHariIni = $toDay == date('Y-m-d', strtotime($dataPasangBaru->created_at));

        try {
            $validator = Validator::make($request->all(), [
                'status' => ['required']
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $data = [
                'status' => $request->input('status')
            ];

            DB::transaction(function() use ($dataPasangBaru, $data, $teknisiNonJob, $pasangBaruNonJob, $pasangBaruHariIni){
                $dataPasangBaru->update($data);

                if ($dataPasangBaru->status == '3') {
                    if ($teknisiNonJob && $pasangBaruNonJob) {
                        $dataJobPasangBaru = DataJob::create([
                            'user_id' => $teknisiNonJob->id,
                            'kode_pasang_baru' => $pasangBaruNonJob->id
                        ]);

                        if (!is_null($dataJobPasangBaru)) {
                            TeknisiCadangan::create([
                                'user_id' => $dataPasangBaru->data_job->user_id
                            ]);
                        }

                    }elseif (!$teknisiNonJob && $pasangBaruNonJob) {
                        DataJob::create([
                            'user_id' => $dataPasangBaru->data_job->user_id,
                            'kode_pasang_baru' => $pasangBaruNonJob->id
                        ]);
                    }elseif (!$teknisiNonJob && !$pasangBaruNonJob && $pasangBaruHariIni) {
                        TeknisiCadangan::create([
                            'user_id' => $dataPasangBaru->data_job->user_id
                        ]);
                    }
                }
            });

            Alert::success('Sukses','Data Pasang Baru berhasil diupdate');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            Alert::error('Oops', 'Data Error');
        }

        return redirect()->back();
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
