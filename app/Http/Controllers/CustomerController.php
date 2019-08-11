<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Contact;
use App\Company;
use App\CustomerGroup;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CustomerController extends Controller
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
        if(!\Auth::user()->allowView(config('global.modules.customer'))) {
            abort(401);
        }

        $search = Input::get('search');
        $group = Input::get('group');

        $groups = CustomerGroup::where('is_delete', 0)->orderBy('group_name')->get();
        
        $customers = Customer::where('is_delete', 0);

        if($group != null) {
            $customers = $customers->where('group_id', $group);    
        }

        $customers = $customers->whereHas('contact', function($data) use ($search) {
                                $data->where('contact_name','LIKE','%'.$search.'%')
                                ->orWhere('primary_telephone','LIKE','%'.$search.'%');
                            })
                            ->orderBy('created_at', 'DESC')->paginate(config('global.page_limit'));

        return view('customers.index', ['customers' => $customers, 'groups' => $groups]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $groups = CustomerGroup::where('is_delete', 0)->orderBy('group_name')->get();
        $contacts = Contact::where('is_delete', 0)->get();        
        $companies = Company::where('is_delete', 0)->orderBy('company_name')->get();
        return view('customers.create', ['groups' => $groups, 'contacts' => $contacts, 'companies' => $companies]);
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
                    'group' => 'required|integer|min:1',
                ], [
                    'group.min' => __('message.field_required')
                ]);

                // redirect to the page when it failed
                if ($validator->fails()) {
                    return redirect()
                                ->route('customer.create', ['type' => $request->type])
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

                $customer = new Customer();
                $customer->code = config('global.codes.customer');
                $customer->contact_id = $item->id;
                $customer->group_id = $request->group;
                $customer->type = $request->type;
                $customer->company_id = $request->company;
                $customer->created_by = \Auth::user()->id;
                $customer->save();

            } else {
                
                $validator = Validator::make($request->all(), [
                    'company_name' => 'required|unique:companies|min:3|max:100',
                    'contact' => 'required|integer|min:1',
                    'group' => 'required|integer|min:1',
                ], [
                    'contact.min' => __('message.field_required'),
                    'group.min' => __('message.field_required')
                ]);

                // redirect to the page when it failed
                if ($validator->fails()) {
                    return redirect()
                                ->route('customer.create', ['type' => $request->type])
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

                $customer = new Customer();
                $customer->code = config('global.codes.customer');
                $customer->contact_id = $request->contact;
                $customer->group_id = $request->group;
                $customer->type = $request->type;
                $customer->company_id = $item->id;
                $customer->created_by = \Auth::user()->id;
                $customer->save();              
            }

            // update supplier code
            $customer->code = $customer->code.$customer->id;
            $customer->save();            

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

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = Customer::findOrFail($id);
        if($item->is_delete == 1) {
            abort(401);
        }

        $groups = CustomerGroup::where('is_delete', 0)->orderBy('group_name')->get();
        $companies = Company::where('is_delete', 0)->orderBy('company_name')->get();
        return view('customers.update', ['groups' => $groups, 'companies'=> $companies, 'customer' => $item]);
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
            'group' => 'required|integer|min:1',
        ]);

        // redirect to the page when it failed
        if ($validator->fails()) {
            return redirect()
                        ->route('customer.edit', $id)
                        ->withErrors($validator)
                        ->withInput();
        }

        // Start transaction!
        DB::beginTransaction();

        try {

            // updating customer in the database
            $customer = Customer::findOrFail($id);
            $customer->group_id = $request->group;
            $customer->type = $request->company == 0 ? 'individual' : 'company';
            $customer->company_id = $request->company;
            $customer->updated_by = \Auth::user()->id;
            $customer->save();


            // updating contact in the database
            $item = Contact::findOrFail($customer->contact_id);
            
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
        $item = Customer::findOrFail($id);
        $item->is_delete = 1;
        $item->save();

        return redirect()->back()->with('message', __('message.delete_successful'));
    }
}
