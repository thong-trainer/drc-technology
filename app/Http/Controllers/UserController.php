<?php

namespace App\Http\Controllers;

use App\User;
use App\Role;
use App\Location;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;

class UserController extends Controller
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
        if(!\Auth::user()->allowView(config('global.modules.user'))) {
            abort(401);
        }

        $search = Input::get ('search');

        $users = User::where('is_delete', 0)
                        ->where('is_hide', 0)
                        ->where(function($data) use ($search) {
                            $data->where('name','LIKE','%'.$search.'%')
                            ->orWhere('email','LIKE','%'.$search.'%');
                        })
                        ->orderBy('name')
                        ->paginate(config('global.page_limit'));              



        return view('setups.users.index', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::where('is_delete', 0)->get();
        $locations = Location::list();
        return view('setups.users.create', ['roles' => $roles, 'locations' => $locations]);
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
            'name' => 'required|min:3|max:100',
            'email' => 'required|unique:users|max:190',
            'role' => 'required|integer|min:1',
            'location_id' => 'required|integer|min:1',
        ],[
            'role.min' => __('message.field_required'),
            'location_id.required' => __('message.field_required'),
            'location_id.min' => __('message.field_required'),
        ]);

        // redirect to the page when it failed
        if ($validator->fails()) {
            return redirect()
                        ->route('user.create')
                        ->withErrors($validator)
                        ->withInput();
        }


        User::createRecord($request);
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
        $item = User::findOrFail($id);
        if($item->is_delete == 1) {
            abort(401);
        }

        $roles = Role::get();
        $locations = Location::list();
        return view('setups.users.update', ['roles' => $roles, 'locations' => $locations, 'user' => $item]);
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
            'name' => 'required|min:3|max:100',
            'role' => 'required|integer|min:1',       
            'location_id' => 'required|integer|min:1',            
        ],[
            'role.min' => __('message.field_required'),
            'location_id.required' => __('message.field_required'),
            'location_id.min' => __('message.field_required'),            
        ]);

        // redirect to the page when it failed
        if ($validator->fails()) {
            return redirect()
                        ->route('user.edit', $id)
                        ->withErrors($validator)
                        ->withInput();
        }

        User::updateRecord($request, $id);
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
        $item = User::findOrFail($id);
        $item->is_delete = 1;
        $item->save();
        return redirect()->back()->with('message', __('message.delete_successful'));
    }
}
