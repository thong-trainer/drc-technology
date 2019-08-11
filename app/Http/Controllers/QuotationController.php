<?php

namespace App\Http\Controllers;
use App\Quotation;
use App\QuotationDetail;
use App\Customer;
use App\PaymentTerm;
use App\Currency;
use App\Product;
use App\CurrencyExchangeRate;
use App\DeliveryMethod;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;


class QuotationController extends Controller
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
            'customer_id' => 'required',
            'validity' => 'required',
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
        if(!\Auth::user()->allowView(config('global.modules.quotation'))) {
            abort(401);
        }

        $search = Input::get ('search');
        $status = Input::get ('status');

        $quotations = Quotation::where(function($data) use ($search) {
                                    $data->where('quotation_number','LIKE','%'.$search.'%')
                                    ->orWhere('quotation_date','LIKE','%'.$search.'%');
                                });
        if($status != null) {
            $quotations = $quotations->where('status', $status);    
        }

        $quotations = $quotations->orderBy('created_at', 'DESC')
                                ->paginate(config('global.page_limit'));        

        return view('quotations.index', ['quotations' => $quotations]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = Customer::list();
        $terms = PaymentTerm::list();
        $currencies = Currency::list();
        $products = Product::list();
        $delivery_methods = DeliveryMethod::list();

        return view('quotations.create', ['customers'=>$customers, 'terms'=>$terms, 'currencies'=>$currencies, 'products'=>$products, 'delivery_methods'=>$delivery_methods]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {                    
        $default_currency = Currency::default();
        $exchange_rate_id = $default_currency->id;
        $selected_currency = Currency::findOrFail($request->current_currency_id);
        $exchange_rate = CurrencyExchangeRate::currentRate($selected_currency->id);
        $calculation = ($selected_currency->calculation == 'multiplication') ? 'divide' : 'multiplication';
        $rate = 1;
    
        // Start transaction!
        DB::beginTransaction();

        try {
            // ...
            $quotation = new Quotation();
            $quotation->quotation_number = config('global.codes.quotation');
            $quotation->customer_id = $request->customer_id;
            $quotation->quotation_date = \Carbon\Carbon::now();
            $quotation->validity_date = $request->validity;
            $quotation->payment_term_id = $request->payment_term_id;
            $quotation->delivery_method_id = $request->delivery_method_id;            
            $quotation->amount = $request->amount;
            $quotation->tax = $request->pay_tax;
            $quotation->discount = $request->discount;
            $quotation->discount_amount = $request->discount_amount;
            $quotation->grand_total = $request->grand_total;
            $quotation->rate = $rate;
            $quotation->currency_id = $default_currency->id;
            // ...
            // exchange money to default currency if the selected currency is not default currency
            if($default_currency->id != $selected_currency->id) {         
                $rate = $exchange_rate->rate;
                $quotation->amount = $this->exchangeRate($calculation, $request->amount, $rate);
                $quotation->tax = $this->exchangeRate($calculation, $request->pay_tax, $rate);
                $quotation->discount_amount = $this->exchangeRate($calculation, $request->discount_amount, $rate);
                $quotation->grand_total = $this->exchangeRate($calculation, $request->grand_total, $rate);
                $quotation->rate = $rate;
                $quotation->is_default_currency = 0;
                $quotation->currency_id = $selected_currency->id;
            }

            $quotation->save();

            // retreive the id and combine with the quotation code
            $quotation->quotation_number = $quotation->quotation_number.$quotation->id;
            $quotation->save();
            // ...
            // create quotation details reference to quotation id above
            foreach ($request->product_id_array as $key => $value) {
                $detail = new QuotationDetail();
                $detail->quotation_id = $quotation->id;
                $detail->product_id = $request->product_id_array[$key];
                $detail->product_name = $request->product_name_array[$key];
                $detail->notes = $request->description_array[$key];
                $detail->variant_ids = $request->variant_ids[$key];
                $detail->qty = $request->qty_array[$key];
                $detail->unit_price = $request->price_array[$key];
                $detail->tax = $request->tax_array[$key];
                $detail->pay_tax = $request->pay_tax_array[$key];
                $detail->discount = $request->discount_array[$key];
                $detail->discount_amount = $request->discount_amount_array[$key];
                $detail->subtotal = $request->subtotal_array[$key];
                // ...
                // exchange money to default currency if the selected currency is not default currency
                if($default_currency->id != $selected_currency->id) { 
                    $detail->pay_tax = $this->exchangeRate($calculation, $request->pay_tax_array[$key], $rate);
                    $detail->discount_amount = $this->exchangeRate($calculation, $request->discount_amount_array[$key], $rate);
                    $detail->unit_price = $this->exchangeRate($calculation, $request->price_array[$key], $rate);
                    $detail->subtotal = $this->exchangeRate($calculation, $request->subtotal_array[$key], $rate);
                }     

                $detail->save();
            }

        } catch(\Exception $e)
        {
            DB::rollback();
            throw $e;
        }

        DB::commit();         
        return response()->json([ 'quotation' => $quotation, 'status' => 'success']);    
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
        $quotation = Quotation::findOrFail($id);
        $customers = Customer::list();
        $terms = PaymentTerm::list();
        $currencies = Currency::list();
        $products = Product::list();
        $delivery_methods = DeliveryMethod::list();

        return view('quotations.update', ['customers'=>$customers, 'terms'=>$terms, 'currencies'=>$currencies, 'products'=>$products, 'quotation' => $quotation, 'delivery_methods'=>$delivery_methods]);
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
        // return response()->json([ 'data' => $request->all(), 'status' => 'success']);   

        // return response()->json([ 'data' => $request->all(), 'status' => 'success']);   

        $default_currency = Currency::default();
        $exchange_rate_id = $default_currency->id;
        $selected_currency = Currency::findOrFail($request->current_currency_id);
        $exchange_rate = CurrencyExchangeRate::currentRate($selected_currency->id);
        $calculation = ($selected_currency->calculation == 'multiplication') ? 'divide' : 'multiplication';
        $rate = 1;
    
        // Start transaction!
        DB::beginTransaction();

        try {
            // ...
            $quotation = Quotation::findOrFail($id);
            $quotation->customer_id = $request->customer_id;
            $quotation->validity_date = $request->validity;
            $quotation->payment_term_id = $request->payment_term_id;
            $quotation->delivery_method_id = $request->delivery_method_id;            
            $quotation->amount = $request->amount;
            $quotation->tax = $request->pay_tax;
            $quotation->discount = $request->discount;
            $quotation->discount_amount = $request->discount_amount;
            $quotation->grand_total = $request->grand_total;
            $quotation->rate = $rate;
            $quotation->currency_id = $default_currency->id;
            // ...
            // exchange money to default currency if the selected currency is not default currency
            if($default_currency->id != $selected_currency->id) {         
                $rate = $exchange_rate->rate;
                $quotation->amount = $this->exchangeRate($calculation, $request->amount, $rate);
                $quotation->tax = $this->exchangeRate($calculation, $request->pay_tax, $rate);
                $quotation->discount_amount = $this->exchangeRate($calculation, $request->discount_amount, $rate);
                $quotation->grand_total = $this->exchangeRate($calculation, $request->grand_total, $rate);
                $quotation->rate = $rate;
                $quotation->is_default_currency = 0;
                $quotation->currency_id = $selected_currency->id;
            }

            $quotation->save();

            // ...
            // create quotation details reference to quotation id above
            QuotationDetail::where('quotation_id', $quotation->id)->delete();
            foreach ($request->product_id_array as $key => $value) {
                $detail = new QuotationDetail();
                $detail->quotation_id = $quotation->id;
                $detail->product_id = $request->product_id_array[$key];
                $detail->product_name = $request->product_name_array[$key];
                $detail->notes = $request->description_array[$key];
                $detail->variant_ids = $request->variant_ids[$key];
                $detail->qty = $request->qty_array[$key];
                $detail->unit_price = $request->price_array[$key];
                $detail->tax = $request->tax_array[$key];
                $detail->pay_tax = $request->pay_tax_array[$key];
                $detail->discount = $request->discount_array[$key];
                $detail->discount_amount = $request->discount_amount_array[$key];
                $detail->subtotal = $request->subtotal_array[$key];
                // ...
                // exchange money to default currency if the selected currency is not default currency
                if($default_currency->id != $selected_currency->id) { 
                    $detail->pay_tax = $this->exchangeRate($calculation, $request->pay_tax_array[$key], $rate);
                    $detail->discount_amount = $this->exchangeRate($calculation, $request->discount_amount_array[$key], $rate);
                    $detail->unit_price = $this->exchangeRate($calculation, $request->price_array[$key], $rate);
                    $detail->subtotal = $this->exchangeRate($calculation, $request->subtotal_array[$key], $rate);
                }     

                $detail->save();
            }

        } catch(\Exception $e)
        {
            DB::rollback();
            throw $e;
        }

        DB::commit();         
        return response()->json([ 'quotation' => $quotation, 'status' => 'success']);   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {        
        Quotation::deleteItem($id);
        return redirect()->back()->with('message', __('message.delete_successful'));
    }

    public function confirm(Request $request, $id)
    {
        Quotation::confirmItem($id);
        return redirect()->back()->with('message', __('message.confirm_successful'));        
    }

    public function createInvoice(Request $request, $id)
    {
        dd($id);  
    }    

    public function print($id)
    {
        $item = Quotation::findOrFail($id);
        view()->share('item', $item);
        return view('quotations.print');
    }   
}
