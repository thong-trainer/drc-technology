<?php

namespace App\Http\Controllers;
use App\Dimension;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class DimensionController extends Controller
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
            'dimension_name' => 'required|min:3|max:100|unique:dimensions,dimension_name,'.$id,
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

        $dimensions = Dimension::where('is_delete', 0)
                                ->orderBy('dimension_name')
                                ->paginate(config('global.page_limit'));
                                
        return view('setups.dimensions.index', ['dimensions' => $dimensions]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('setups.dimensions.create');    
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
                        ->route('unit.create')
                        ->withErrors($validator)
                        ->withInput();
        }

        Dimension::createItem($request);
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
        $item = Dimension::findOrFail($id);
        if($item->is_delete == 1) {
            abort(401);
        }

        return view('setups.dimensions.update', ['dimension' => $item]); 
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
                        ->route('dimension.edit', $id)
                        ->withErrors($validator)
                        ->withInput();
        }        

        Dimension::updateItem($request, $id);
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
        Dimension::deleteItem($id);
        return redirect()->back()->with('message', __('message.delete_successful'));
    }
}
