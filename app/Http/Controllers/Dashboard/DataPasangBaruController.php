<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

use App\Models\Setting;
use App\Models\DataPasangBaru;

use DataTables;
use Carbon\Carbon;

class DataPasangBaruController extends Controller
{
    public function index()
    {
        $title = 'Data Pasang Baru';
        $appName = Setting::first();

        return view('dashboard.data_pasang_baru.index', compact('title','appName'));
    }

    public function getJsonPasangBaru(Request $request)
    {
        if ($request->ajax()) {
			$data = DataPasangBaru::select('*')->orderBy('created_at','DESC');
            
            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
					if ($request->get('status') == '0' || $request->get('status') == '1' || $request->get('status') == '2' || $request->get('status') == '3') {
                        $instance->where('status', $request->get('status'));
                    }

                    if (!empty($request->get('search'))) {
                            $instance->where(function($w) use($request){
                            $search = $request->get('search');
                            $w->orWhere('data_pasang_barus.kode', 'LIKE', "%$search%")
							->orWhere('data_pasang_barus.nama_pelanggan', 'LIKE', "%$search%")
							->orWhere('data_pasang_barus.no_hp', 'LIKE', "%$search%")
							->orWhere('data_pasang_barus.alamat', 'LIKE', "%$search%")
							->orWhere('data_pasang_barus.acuan_lokasi', 'LIKE', "%$search%");
                        });
                    }
                })

                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? with(new Carbon($row->created_at))->isoFormat('LLLL') : '';
                })

                ->addColumn('updated_at', function ($row) {
                    return $row->updated_at ? with(new Carbon($row->updated_at))->isoFormat('LLLL') : '';
                })

                ->addColumn('action', function($row){
					$btn = '<a href="data_pasang_baru/'.$row->id.'" class="btn btn-primary" style="padding: 7px 10px">Detail</a>';
                    $btn = $btn.' <a href="data_pasang_baru/edit/'.$row->id.'" class="btn btn-warning" style="padding: 7px 10px">Edit</a>';
                    $btn = $btn.' <button type="button" href="data_pasang_baru/hapus/'.$row->id.'" class="btn btn-danger btn-hapus" style="padding: 7px 10px">Delete</button>';
                    return $btn;
                })

                ->addColumn('status', function($row){
                    if($row->status == 0){
                        return '<span class="badge badge-info">Open</span>';
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
		$request->validate([
			'kode' => 'required',
            'nama_pelanggan' => 'required',
            'no_hp' => 'required',
            'alamat' => 'required',
            'acuan_lokasi' => 'required',
            'foto' => 'file|mimes:jpg,jpeg,png|max:1024'
		]);

        $data['kode'] = $request->kode;
		$data['nama_pelanggan'] = $request->nama_pelanggan;
		$data['no_hp'] = $request->no_hp;
		$data['alamat'] = $request->alamat;
		$data['acuan_lokasi'] = $request->acuan_lokasi;
		$data['created_at'] = date('Y-m-d H:i:s');
		$data['updated_at'] = date('Y-m-d H:i:s');

        $file = $request->file('foto');
        if($file){
            $nama_file = rand().'-'. $file->getClientOriginalName();
            $file->move('assets',$nama_file);
            $data['foto'] = 'assets/' .$nama_file;
        }

		DataPasangBaru::insert($data);
        Alert::success('Sukses','Data Pasang Baru berhasil disimpan');
		return redirect()->back();
	}

    public function detail($id)
    {
        $title = 'Detail Pasang Baru';
        $appName = Setting::first();
        $data = DataPasangBaru::find($id);
        $listPasangBaru = DataPasangBaru::orderBy('created_at','DESC')->get();

        if($data->status == 0){
            $badge = 'badge-info';
            $status = 'Open';
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

    public function edit($id)
    {
        $title = 'Edit Pasang Baru';
        $appName = Setting::first();
        $data = DataPasangBaru::find($id);
        $listPasangBaru = DataPasangBaru::orderBy('created_at','DESC')->get();

        return view('dashboard.data_pasang_baru.edit', compact('title','appName','data','listPasangBaru'));
    }

    public function update(Request $request, $id)
	{
		$request->validate([
			'kode' => 'required',
            'nama_pelanggan' => 'required',
            'no_hp' => 'required',
            'alamat' => 'required',
            'acuan_lokasi' => 'required',
            'foto' => 'file|mimes:jpg,jpeg,png|max:1024'
		]);

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
		return redirect()->back();
	}

    public function delete($id){
        try {
            DataPasangBaru::where('id',$id)->delete();

            Alert::success('Sukses','Data Pasang Baru berhasil dihapus');
        } catch (\Exception $e) {
            Alert::error('Error',$e->getMessage());
        }
        return redirect()->back();
    }
}
