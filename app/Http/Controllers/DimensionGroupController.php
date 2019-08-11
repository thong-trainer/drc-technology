<?php

namespace App\Http\Controllers;

use App\Dimension;
use App\DimensionGroup;
use App\DimensionDetail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DimensionGroupController extends Controller
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
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data, $id="")
    {
        return Validator::make($data, [
            'dimension_group' => 'required|min:3|max:100|unique:dimension_groups,dimension_group,'.$id,
            'dimensions' => 'required',
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // check view permission on the user profile
        if(!\Auth::user()->allowView(config('global.modules.dimension'))) {
            abort(401);
        }

        $groups = DimensionGroup::where('is_delete', 0)->orderBy('dimension_group')->paginate(config('global.page_limit'));
        return view('setups.dimension-groups.index', ['groups' => $groups]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dimensions = Dimension::list();
        return view('setups.dimension-groups.create', ['dimensions' => $dimensions]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $this->validator($request->all());
        
        // redirect to the page when it failed
        if ($validator->fails()) {
            return redirect()
                        ->route('dimension-group.create')
                        ->withErrors($validator)
                        ->withInput();
        } 

        DimensionGroup::createItem($request);
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
    public function edit( $id)
    {
        $item = DimensionGroup::findOrFail($id);
        if($item->is_delete == 1) {
            abort(401);
        }

        $dimensions = Dimension::list();
        return view('setups.dimension-groups.update', ['group' => $item, 'dimensions' => $dimensions]); 
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
        $validator = $this->validator($request->all(), $id);

        // redirect to the page when it failed
        if ($validator->fails()) {
            return redirect()
                        ->route('dimension-group.edit', $id)
                        ->withErrors($validator)
                        ->withInput();
        }       

        DimensionGroup::updateItem($request, $id);
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
        DimensionGroup::deleteItem($id);
        return redirect()->back()->with('message', __('message.delete_successful'));
    }
}
