<?php

namespace App\Http\Controllers;
use App\Invoice;
use App\Quotation;
use App\InvoiceDetail;
use App\Stock;
use App\StockMovement;
use App\InvoicePayment;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;

class InvoiceController extends Controller
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
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'customer_id' => 'required',
            'quotation_id' => 'required',
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
        if(!\Auth::user()->allowView(config('global.modules.invoice'))) {
            abort(401);
        }

        $search = Input::get ('search');
        $status = Input::get ('status');

        $invoices = Invoice::where(function($data) use ($search) {
                                    $data->where('invoice_number','LIKE','%'.$search.'%')
                                    ->orWhere('issue_date','LIKE','%'.$search.'%')
                                    ->orWhere('due_date','LIKE','%'.$search.'%');
                                });
        if($status != null) {
            $invoices = $invoices->where('status', $status);    
        }

        $invoices = $invoices->orderBy('created_at', 'DESC')
                                ->paginate(config('global.page_limit'));        

        return view('invoices.index', ['invoices' => $invoices]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $location_id = \Auth::user()->location_id;
        $quotation = Quotation::findOrFail($request->quotation_id);
        // dd($quotation);

        // Start transaction!
        DB::beginTransaction();

        try {
            $invoice = new Invoice();
            $invoice->invoice_number = config('global.codes.invoice');
            $invoice->quotation_id = $quotation->id;
            $invoice->customer_id = $quotation->customer_id;
            $invoice->salesperson_id = $quotation->created_by;
            $invoice->issue_date = \Carbon\Carbon::now();
            $invoice->due_date = \Carbon\Carbon::now();
            $invoice->payment_term_id = $quotation->payment_term_id;
            $invoice->delivery_method_id = $quotation->delivery_method_id;            
            $invoice->amount = $quotation->amount;
            $invoice->tax = $quotation->tax;
            $invoice->discount = $quotation->discount;
            $invoice->discount_amount = $quotation->discount_amount;
            $invoice->grand_total = $quotation->grand_total;
            $invoice->rate = $quotation->rate;
            $invoice->currency_id = $quotation->currency_id;
            $invoice->save();

            // retreive the id and combine with the invoice code
            $invoice->invoice_number = $invoice->invoice_number.$invoice->id;
            $invoice->save();            
            // ...

            // Stock Movement Created
            $result = StockMovement::createStockForSaleOrder($invoice->customer_id, $quotation->quotation_number);
            // Insert Details
            foreach ($quotation->details as $key => $item) {
                $detail = new InvoiceDetail();
                $detail->invoice_id = $invoice->id;
                $detail->product_id = $item->product_id;
                $detail->product_name = $item->product_name;
                $detail->unit_price = $item->unit_price;
                $detail->qty = $item->qty;
                $detail->tax = $item->tax;
                $detail->pay_tax = $item->pay_tax;
                $detail->discount = $item->discount;
                $detail->discount_amount = $item->discount_amount;
                $detail->subtotal = $item->subtotal;
                $detail->notes = $item->notes;
                $detail->save();

                // cut stock
                $stock = new Stock();
                $stock->stock_movement_id = $result->id;
                $stock->product_id = $item->product_id;
                $stock->initial_qty = $item->qty;
                $stock->done_qty = $item->qty;
                $stock->save();
            }


            // Update Quotation Status
            Quotation::invoiceCreated($quotation->id);
        } catch(\Exception $e)
        {
            DB::rollback();
            throw $e;
        }

        DB::commit();   
        return redirect()->route('invoice.show', $invoice->id.'/show');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoice = Invoice::findOrFail($id);
        return view('invoices.show', ['item' => $invoice]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        DB::beginTransaction();

        try {
            InvoicePayment::createItem($request, $id);

            $invoice = Invoice::findOrFail($id);
            
            if($invoice->grand_total <= $invoice->payments->sum('amount')) {
                $invoice->status = config('global.invoice_status.paid');
                $invoice->save();
            }

        } catch(\Exception $e)
        {
            DB::rollback();
            throw $e;
        }

        DB::commit();                       
        return redirect()->back()->with('message', __('message.payment_successful'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function print($id)
    {
        $item = Invoice::findOrFail($id);
        view()->share('item', $item);
        return view('invoices.print');
    }   
}
