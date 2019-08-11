<?php

namespace App\Http\Controllers;
use App\Company;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;

class CompanyController extends Controller
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
        if(!\Auth::user()->allowView(config('global.modules.company'))) {
            abort(401);
        }

        $search = Input::get ('search');

        $companies = Company::where('is_delete', 0)
                        ->where('is_enable', 1)
                        ->where(function($data) use ($search) {
                            $data->where('company_name','LIKE','%'.$search.'%')
                            ->orWhere('email','LIKE','%'.$search.'%');
                        })
                        ->orderBy('company_name')
                        ->paginate(config('global.page_limit'));

        return view('companies.index', ['companies' => $companies]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = Company::findOrFail($id);
        if($item->is_delete == 1) {
            abort(401);
        }

        return view('companies.show', ['company' => $item]);  
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = Company::findOrFail($id);
        if($item->is_delete == 1) {
            abort(401);
        }

        return view('companies.update', ['company' => $item]);  
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Company::findOrFail($id);
        $item->is_delete = 1;
        $item->save();

        return redirect()->back()->with('message', __('message.delete_successful'));
    }
}
