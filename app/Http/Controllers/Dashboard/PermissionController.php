<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:permission-list|permission-create|permission-edit|permission-delete', ['only' => ['index','show']]);
        $this->middleware('permission:permission-create', ['only' => ['create','store']]);
        $this->middleware('permission:permission-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:permission-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $title = 'Data Role';
        $appName = Setting::first();

        return view('dashboard.permission.index', compact('title','appName'));
    }

    public function getJsonPermission(Request $request)
    {
        if ($request->ajax()) {
            $data = Permission::select('*');
            
            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                            $instance->where(function($w) use($request){
                            $search = $request->get('search');
                            $w->orWhere('permissions.name', 'LIKE', "%$search%");
                        });
                    }
                })

                ->addColumn('action', function($row){
					$btn = '<a href="permission/edit/'.$row->id.'" class="btn btn-warning" style="padding: 7px 10px">Edit</a>';
                    $btn = $btn.' <button type="button" href="permission/'.$row->id.'/destroy" class="btn btn-danger btn-hapus" style="padding: 7px 10px">Delete</button>';
                    return $btn;
                })

                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }

        return response()->json(true);
    }

    public function store(Request $request){
        $this->validate($request,[
            'name' => 'required',
        ]);

        $data['name'] = $request->name;
        $data['guard_name'] = 'web';
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        Permission::insert($data);

        Alert::success('Sukses','Permission Berhasil ditambah');
        return redirect()->back();
    }

    public function edit($id)
    {
        $title = 'Edit Permission';
        $appName = Setting::first();
        $data = Permission::findOrFail($id);

        return view('dashboard.permission.edit',compact('title','appName','data'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);
    
        $data['name'] = $request->name;
        // $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        Permission::where('id',$id)->update($data);

        Alert::success('Sukses','Permission berhasil diupdate');
        return redirect()->route('permission.index');
    }
}
