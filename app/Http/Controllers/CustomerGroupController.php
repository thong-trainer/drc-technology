<?php

namespace App\Http\Controllers;

use App\CustomerGroup;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CustomerGroupController extends Controller
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
        if(!\Auth::user()->allowView(config('global.modules.customer_group'))) {
            abort(401);
        }


        $groups = CustomerGroup::where('is_delete', 0)->orderBy('group_name')->get();
        return view('customer-groups.index', ['groups' => $groups]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('customer-groups.create');
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
            'group_name' => 'required|min:3|max:100',
            'description' => 'required|max:190',
        ]);

        // redirect to the page when it failed
        if ($validator->fails()) {
            return redirect()
                        ->route('customer-group.create')
                        ->withErrors($validator)
                        ->withInput();
        }
        
        // saving to the database
        $item = new CustomerGroup();
        $item->group_name = $request->group_name;
        $item->description = $request->description;
        $item->created_by = \Auth::user()->id;
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
        $item = CustomerGroup::findOrFail($id);
        if($item->is_delete == 1) {
            abort(401);
        }

        return view('customer-groups.show', ['group' => $item]); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = CustomerGroup::findOrFail($id);
        if($item->is_delete == 1) {
            abort(401);
        }

        return view('customer-groups.update', ['group' => $item]); 
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
            'group_name' => 'required|min:3|max:100',
            'description' => 'required|max:190',
        ]);

        // redirect to the page when it failed
        if ($validator->fails()) {
            return redirect()
                        ->route('customer-group.edit', $id)
                        ->withErrors($validator)
                        ->withInput();
        }

        // updating to the database
        $item = CustomerGroup::findOrFail($id);
        $item->group_name = $request->group_name;
        $item->description = $request->description;
        $item->updated_by = \Auth::user()->id;
        $item->save();        

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
        $item = CustomerGroup::findOrFail($id);
        $item->is_delete = 1;
        $item->save();

        return redirect()->back()->with('message', __('message.delete_successful'));
    }
}
