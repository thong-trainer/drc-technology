<?php

namespace App\Http\Controllers;

use App\ProductVariant;
use App\Product;
use App\DimensionGroup;
use App\DimensionDetail;
use App\Currency;
use App\CurrencyExchangeRate;
use App\ProductPrice;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
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

    public function extraPrices($product_id) {
        // check view permission on the user profile
        if(!\Auth::user()->allowView(config('global.modules.product_price'))) {
            abort(401);
        }

        $product = Product::findOrFail($product_id);
        $items = ProductVariant::listByProductId($product_id);
        return view('product-prices.extra-price', ['data' => $items, 'product' => $product]);
    }

    public function list($product_id, $dimension_group_id)
    {
        $dimension_group = DimensionGroup::findOrFail($dimension_group_id);
        $product_variants = ProductVariant::listByProductId($product_id);
        return view('product-prices.variant', ['dimension_details' => $dimension_group->details, 'product_variants' => $product_variants, 'product_id' => $product_id]);        
    }

    public function variantsByProductId($product_id, $currency_id, $customer_group_id) {
        $product = Product::findOrFail($product_id);
        $currency = Currency::findOrFail($currency_id);
        $exchange_rate = CurrencyExchangeRate::currentRate($currency_id)->rate;
        $items = ProductVariant::listByProductId($product_id);
        $default_price = ProductPrice::defaultPrice($product_id, $customer_group_id);
        

        foreach ($items as $key => $item) {
            $item->extra_price = $this->exchangeRate($currency->calculation, $item->extra_price, $exchange_rate);
        }
        
        $default_price = $this->exchangeRate($currency->calculation, $default_price, $exchange_rate);

        return view('products.price', ['data' => $items, 'currency' => $currency, 'default_price' => $default_price, 'product' => $product]);
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        ProductVariant::updatePrice($id, $request->price);
        return redirect()->back()->with('message', __('message.update_successful'));
    }

    public function updateVariants(Request $request, $product_id)
    {
        // Start transaction!
        DB::beginTransaction();

        try {
            $product = Product::findOrFail($product_id);
            ProductVariant::where('product_id', $product_id)->delete();
            $details = DimensionDetail::where('group_id', $product->dimension_group_id)->get();
           
            $data = array();
            $index = 0;

            foreach ($details as $key => $item) {

                $attribute_values = $request[$item->dimension_id.'_values'];
                
                if(!empty($attribute_values)) {
                    foreach ($attribute_values as  $value) {
                        $has = ProductVariant::where('product_id', $product_id)
                                                ->where('dimension_id', $item->dimension_id)
                                                ->where('value', '=', $value)->first();
                        if(empty($has)) {
                            $data[$index]['product_id'] = $product_id;
                            $data[$index]['dimension_id'] = $item->dimension_id;
                            $data[$index]['value'] = $value;
                            $index = $index + 1;
                        } 
                    }
                }
            }

            ProductVariant::insertItems($data, $product_id); 
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
        //
    }
}
