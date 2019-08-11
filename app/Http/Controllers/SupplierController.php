<?php

namespace App\Http\Controllers;
use App\Contact;
use App\Company;
use App\Supplier;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SupplierController extends Controller
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
        if(!\Auth::user()->allowView(config('global.modules.supplier'))) {
            abort(401);
        }

        $search = Input::get('search');
        $company = Input::get('company');
        
        $suppliers = Supplier::where('is_delete', 0);

        if($company != null) {
            $suppliers = $suppliers->where('company_id', $company);    
        }

        $suppliers = $suppliers->whereHas('contact', function($data) use ($search) {
                                $data->where('contact_name','LIKE','%'.$search.'%')
                                ->orWhere('primary_telephone','LIKE','%'.$search.'%');
                            })
                            ->orderBy('created_at', 'DESC')->paginate(config('global.page_limit'));

        $companies = Company::where('is_delete', 0)->get();
        // dd($suppliers);
        return view('suppliers.index', ['suppliers' => $suppliers, 'companies' => $companies]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $contacts = Contact::where('is_delete', 0)->get();        
        $companies = Company::where('is_delete', 0)->orderBy('company_name')->get();
        return view('suppliers.create', ['companies' => $companies, 'contacts' => $contacts]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {    
        // Start transaction!
        DB::beginTransaction();

        try {

            if($request->type == 'individual') {
                $validator = Validator::make($request->all(), [
                    'company_name' => 'required|min:3|max:100',
                    'gender' => 'required',
                    'primary_telephone' => 'required|unique:contacts|min:13|max:14',
                ]);

                // redirect to the page when it failed
                if ($validator->fails()) {
                    return redirect()
                                ->route('supplier.create', ['type' => $request->type])
                                ->withErrors($validator)
                                ->withInput();
                }

                // saving to the database
                $item = new Contact();
                $item->contact_name = $request->company_name;
                $item->gender = $request->gender;
                $item->position = $request->position;
                $item->email = $request->email;
                $item->primary_telephone = $this->phoneFormat($request->primary_telephone);
                $item->other_telephone = $request->other_telephone;            
                $item->main_address = $request->address;
                $item->notes = $request->notes;
                $item->image_url = $this->upload($request, config('global.paths.contact'));
                $item->created_by = \Auth::user()->id;
                $item->save();


                // save supplier information
                $supplier = new Supplier();
                $supplier->code = config('global.codes.supplier');
                $supplier->contact_id = $item->id;
                $supplier->type = $request->type;
                $supplier->company_id = $request->company;
                $supplier->created_by = \Auth::user()->id;
                $supplier->save();
            } else {
                
                $validator = Validator::make($request->all(), [
                    'company_name' => 'required|unique:companies|min:3|max:100',
                    'contact' => 'required|integer|min:1',
                ], [
                    'contact.min' => __('message.field_required')
                ]);

                // redirect to the page when it failed
                if ($validator->fails()) {
                    return redirect()
                                ->route('supplier.create', ['type' => $request->type])
                                ->withErrors($validator)
                                ->withInput();
                }

                // saving to the database
                $item = new Company();
                $item->company_name = $request->company_name;
                $item->telephone = $request->other_telephone;
                $item->email = $request->email;
                $item->website = $request->website;
                $item->address = $request->address;
                $item->notes = $request->notes;
                $item->image_url = $this->upload($request, config('global.paths.contact'));
                $item->created_by = \Auth::user()->id;
                $item->save();

                // save supplier information
                $supplier = new Supplier();
                $supplier->code = config('global.codes.supplier');
                $supplier->contact_id = $request->contact;
                $supplier->type = $request->type;
                $supplier->company_id = $item->id;
                $supplier->created_by = \Auth::user()->id;
                $supplier->save();                
            }

            // update supplier code
            $supplier->code = $supplier->code.$supplier->id;
            $supplier->save();            

        } catch(\Exception $e)
        {
            DB::rollback();
            throw $e;
        }

        DB::commit();

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
        $item = Supplier::findOrFail($id);
        if($item->is_delete == 1) {
            abort(401);
        }

        $companies = Company::where('is_delete', 0)->orderBy('company_name')->get();
        return view('suppliers.update', ['companies'=> $companies, 'supplier' => $item]);
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
            'contact_name' => 'required|min:3|max:100',
            'gender' => 'required',
            'primary_telephone' => 'required|min:13|max:14',
        ]);

        // redirect to the page when it failed
        if ($validator->fails()) {
            return redirect()
                        ->route('supplier.edit', $id)
                        ->withErrors($validator)
                        ->withInput();
        }

        // Start transaction!
        DB::beginTransaction();

        try {


            // updating supplier in the database
            $supplier = Supplier::findOrFail($id);
            $supplier->company_id = $request->company;
            $supplier->type = $request->company == 0 ? 'individual' : 'company';
            $supplier->updated_by = \Auth::user()->id;
            $supplier->save();

            // updating contact in the database
            $item = Contact::findOrFail($supplier->contact_id);
            
            // copy a image to 'uploads' folder in 'public' folder
            if ($request->hasFile('file_upload')) {
                $item->image_url =  $this->upload($request, config('global.paths.contact'));
            }    

            $item->contact_name = $request->contact_name;
            $item->gender = $request->gender;
            $item->position = $request->position;
            $item->email = $request->email;
            $item->primary_telephone = $this->phoneFormat($request->primary_telephone);
            $item->other_telephone = $request->other_telephone;            
            $item->main_address = $request->address;
            $item->notes = $request->notes;
            $item->updated_by = \Auth::user()->id;
            $item->save();

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
        $item = Supplier::findOrFail($id);
        $item->is_delete = 1;
        $item->save();

        return redirect()->back()->with('message', __('message.delete_successful'));
    }
}
