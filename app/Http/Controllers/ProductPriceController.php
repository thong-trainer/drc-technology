<?php

namespace App\Http\Controllers;
use App\CustomerGroup;
use App\ProductPrice;
use App\Currency;
use App\ProductVariant;
use App\Product;
use App\CurrencyExchangeRate;
use App\Customer;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;

class ProductPriceController extends Controller
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
        //
    }    

    public function list($product_id)
    {
        $product_prices = ProductPrice::findOrFail($product_id);
        return view('products.prices', ['product_prices' => $product_prices, 'product_id' => $product_id]);        
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

    public function createAjax($product_id)
    {
        $groups = CustomerGroup::list();
        return view('product-prices.create', ['groups' => $groups, 'product_id' => $product_id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        ProductPrice::createItem($request);
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
        //
    }
    
    public function editAjax($id)
    {
        $item = ProductPrice::findOrFail($id);
        $groups = CustomerGroup::list();
        return view('product-prices.update', ['product_price' => $item, 'groups' => $groups]);
    }

    public function priceAjax($customer_group_id, $product_id, $qty, $variant_ids, $currency_id)
    {
        $variant_id_array = explode(',', $variant_ids); 
        $currency = Currency::findOrFail($currency_id);        
        $price = $this->calculatePrice($customer_group_id, $product_id, $qty, $variant_id_array, $currency);            
        return response()->json([
                    'price' => $currency->symbol.' '.number_format($price, $currency->digit),
                    'status' => 'success'
                ]);    
    } 

    function getPrice($customer_group_id, $product_id, $qty, $variant_ids, $currency, $discount, $description = "")
    {
        // definesome necessary objects
        $product = Product::findOrFail($product_id);
        $variant_id_array = explode(',', $variant_ids); 

        // return $variant_id_array;

        // calcation extra price and exchange based on currency
        $price = $this->calculatePrice($customer_group_id, $product_id, $qty, $variant_id_array, $currency);       

        // generate dimension information. we use for description only
        $variant_info = '';
        foreach ($variant_id_array as $key => $value) {
            $variant = ProductVariant::where('id', $value)->first();
            if(!empty($variant)) {
                $variant_info .= $variant->value.', ';    
            }            
        }
        // remove last character of string (comma)
        $str = rtrim($variant_info, ', ');
        $product_name = $product->product_name;
        if(!empty($str)) {
            $product_name = $product_name.' ('.$str.')';
        }
        // calculation discount and tax
        $amount = $price * $qty;
        $pay_tax = ($amount * $product->customer_tax / 100);                
        $discount_amount = ($amount * $discount / 100); 

        return [
                'product_id' => $product_id,
                'product_name' => $product_name,
                'variant_ids' => $variant_ids,
                'price' => $price,
                'qty' => $qty,
                'discount' => $discount,
                'discount_amount' => $discount_amount,
                'tax' => $product->customer_tax,
                'pay_tax' => $pay_tax,
                'subtotal' => $amount,
                'description' => $description,
            ];
    }

    public function generateRecords($customer_group_id, $product_id, $qty, $variant_ids, $currency_id, $discount) 
    {
        $currency = Currency::findOrFail($currency_id);
        $data[0] = $this->getPrice($customer_group_id, $product_id, $qty, $variant_ids, $currency, $discount); 
        // return response()->json([
        //             'data' => $this->getPrice($customer_group_id, $product_id, $qty, $variant_ids, $currency, $discount),
        //             'status' => 'success'
        //         ]);                    
        return view('quotations.generate-row', ['data' => $data, 'currency' => $currency]);
    }

    function changeCurrency(Request $request, $currency_id)
    {
        $discount = $request->discount;
        $currency = Currency::findOrFail($currency_id);
        $customer_group_id = Customer::findOrFail($request->customer_id)->group_id;

        foreach ($request->product_id_array as $key => $value) 
        {
            $product_id = $request->product_id_array[$key];
            $variant_ids = $request->variant_ids[$key];
            $qty = $request->qty_array[$key];
            $description = $request->description_array[$key];
            $data[$key] = $this->getPrice($customer_group_id, $product_id, $qty, $variant_ids, $currency, $discount, $description);
        }

        return view('quotations.generate-row', ['data' => $data, 'currency' => $currency]);
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
        ProductPrice::updateItem($request, $id);
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
        // dd('ID: '.$id);
        ProductPrice::deleteItem($id);
        return redirect()->back()->with('message', __('message.delete_successful'));
    }
}
