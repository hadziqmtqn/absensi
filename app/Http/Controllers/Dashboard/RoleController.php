<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use RealRashid\SweetAlert\Facades\Alert;

class RoleController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
        $this->middleware('permission:role-create', ['only' => ['create','store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $title = 'Data Role';
        $appName = Setting::first();

        return view('dashboard.role.index', compact('title','appName'));
    }

    public function getJsonRole(Request $request)
    {
        if ($request->ajax()) {
            $data = Role::select('*');
            
            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                            $instance->where(function($w) use($request){
                            $search = $request->get('search');
                            $w->orWhere('roles.name', 'LIKE', "%$search%");
                        });
                    }
                })

                ->addColumn('action', function($row){
					$btn = '<a href="role/'.$row->id.'" class="btn btn-primary" style="padding: 7px 10px">Detail</a>';
                    $btn = $btn.' <a href="role/edit/'.$row->id.'" class="btn btn-warning" style="padding: 7px 10px">Edit</a>';
                    $btn = $btn.' <button type="button" href="role/'.$row->id.'/destroy" class="btn btn-danger btn-hapus" style="padding: 7px 10px">Delete</button>';
                    return $btn;
                })

                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }

        return response()->json(true);
    }

    public function detail($id)
    {
        $title = 'Detail Role';
        $appName = Setting::first();
        $data = Role::findOrFail($id);
        $rolePermissions = Permission::join('role_has_permissions','role_has_permissions.permission_id','=','permissions.id')
        ->where('role_has_permissions.role_id',$id)
        ->get();
    
        return view('dashboard.role.detail',compact('title','appName','data','rolePermissions'));
    }

    public function edit($id)
    {
        $title = 'Edit Role';
        $appName = Setting::first();
        $role = Role::findOrFail($id);
        $permission = Permission::get();
        $rolePermissions = DB::table('role_has_permissions')->where('role_has_permissions.role_id',$id)
        ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
        ->all();
    
        return view('dashboard.role.edit',compact('title','appName','role','permission','rolePermissions'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'permission',
        ]);
    
        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();
    
        $role->syncPermissions($request->input('permission'));

        Alert::success('Sukses','Role berhasil diupdate');
        return redirect()->route('role.index');
    }
}
