<?php

namespace App\Http\Controllers;

use App\Role;
use App\Module;
use App\Permission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // check view permission on the user profile
        if(!\Auth::user()->allowView(config('global.modules.role'))) {
            abort(401);
        }

        $roles = Role::where('is_delete', 0)->where('is_hide', 0)->orderBy('role_name')->get();
        return view('setups.roles.index', ['roles' => $roles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('setups.roles.create');        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_name' => 'required|min:3|max:100',
            'description' => 'required|max:190',
        ]);

        // redirect to the page when it failed
        if ($validator->fails()) {
            return redirect()
                        ->route('role.create')
                        ->withErrors($validator)
                        ->withInput();
        }
        // saving to the database
        $item = new Role();
        $item->role_name = $request->role_name;
        $item->description = $request->description;
        $item->save();

        return redirect()->back()->with('message', __('message.save_successful'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = Role::findOrFail($id);
        if($item->is_delete == 1) {
            abort(401);
        }

        $data = array();
        $modules = Module::get();
        foreach ($modules as $key => $module) {
            // $data[$key]['id'] = $module->id;
            $permission = Permission::where('role_id', $item->id)->where('module_id', $module->id)->first();
            if(empty($permission)) {
                $permission = new Permission();
                $permission->role_id = $id;
                $permission->module_id = $module->id;
            }

            $data[$key] = $permission;
        }

        return view('setups.roles.update', ['role' => $item, 'permissions' => $data]); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'role_name' => 'required|min:3|max:100',
            'description' => 'required|max:190',
        ]);

        // redirect to the page when it failed
        if ($validator->fails()) {
            return redirect()
                        ->route('role.edit', $id)
                        ->withErrors($validator)
                        ->withInput();
        }

        // Start transaction!
        DB::beginTransaction();

        try {
            // updating to the database
            $item = Role::findOrFail($id);
            $item->role_name = $request->role_name;
            $item->description = $request->description;
            $item->save();

            // delete permissions based on role
            Permission::where('role_id', $id)->delete();

            // add new permissions
            foreach ($request->modules as $key => $value) {
                $permission = new Permission();
                $permission->role_id = $id;
                $permission->module_id = $request->modules[$key];
                $permission->is_view = ($request->view_array[$key] == "on") ? 1 : 0;
                $permission->is_create = ($request->create_array[$key] == "on") ? 1 : 0;
                $permission->is_edit = ($request->edit_array[$key] == "on") ? 1 : 0;
                $permission->is_delete = ($request->delete_array[$key] == "on") ? 1 : 0;
                $permission->is_export = ($request->export_array[$key] == "on") ? 1 : 0;
                $permission->is_import = ($request->import_array[$key] == "on") ? 1 : 0;
                $permission->save();                    
            }
        } catch(\Exception $e)
        {
            DB::rollback();
            throw $e;
        }

        DB::commit();
        
        return redirect()->back()->with('message', __('message.update_successful'));        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Role::findOrFail($id);
        $item->is_delete = 1;
        $item->save();

        return redirect()->back()->with('message', __('message.delete_successful'));
    }
}
