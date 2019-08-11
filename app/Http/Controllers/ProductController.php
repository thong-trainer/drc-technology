<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use App\DimensionGroup;
use App\DimensionDetail;
use App\Unit;
use App\CustomerGroup;
use App\ProductPrice;
use App\Location;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ProductController extends Controller
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
            'product_name' => 'required|min:3|max:100|unique:products,product_name,'.$id,
            'product_type' => 'required',
            'category_id' => 'required|integer|min:1',
            'dimension_group_id' => 'required|integer|min:1',
            'customer_tax' => 'required|integer|min:0|max:100',
            'sale_price' => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/|min:0',
            'cost' => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/|min:0',
            'barcode' => 'unique:products,barcode,'.$id,
        ],[
            'category_id.min' => __('message.field_required'),
            'dimension_group_id.min' => __('message.field_required'),
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
        if(!\Auth::user()->allowView(config('global.modules.product'))) {
            abort(401);
        }

        $search = Input::get ('search');
        $category = Input::get ('category');
        $status = Input::get ('status');

        $categories = Category::list();
        $products = Product::where(function($data) use ($search) {
                                    $data->where('product_name','LIKE','%'.$search.'%')
                                    ->orWhere('tags','LIKE','%'.$search.'%');
                                });

        if($category != null) {
            $products = $products->where('category_id', $category);    
        }

        if($status != null) {
            $products = $products->where('is_release', $status);       
        }
       
        $products = $products->orderBy('created_at', 'DESC')
                                ->paginate(config('global.page_limit'));

        return view('products.index', ['products' => $products, 'categories' => $categories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::list();
        $dimension_groups = DimensionGroup::list();
        $units = Unit::list();
        return view('products.create', ['categories' => $categories, 'dimension_groups' => $dimension_groups, 'units' => $units]);
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
                        ->route('product.create')
                        ->withErrors($validator)
                        ->withInput();
        }

        $request['image_url'] = $this->upload($request, config('global.paths.product'));
        
                // Start transaction!
        DB::beginTransaction();

        try {

            $product = Product::createItem($request);
            ProductPrice::createDefault($product->id, $request->sale_price);

        } catch(\Exception $e)
        {
            DB::rollback();
            throw $e;
        }

        DB::commit();
        
        return redirect()->route('product.show', $product->id.'/show'); 
        // return redirect()->back()->with('message', __('message.save_successful'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = Product::findOrFail($id);
        if($item->is_delete == 1) {
            abort(401);
        }

        $locations = Location::list();
        return view('products.show', ['product' => $item, 'locations' => $locations]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = Product::findOrFail($id);
        if($item->is_delete == 1) {
            abort(401);
        }

        $categories = Category::list();
        $dimension_groups = DimensionGroup::list();
        $units = Unit::list();

        return view('products.update', ['product'=>$item, 'categories' => $categories, 'dimension_groups' => $dimension_groups, 'units' => $units,]);
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
                        ->route('product.edit', $id)
                        ->withErrors($validator)
                        ->withInput();
        }
        
        if ($request->hasFile('file_upload')) {
            $request['image_url'] = $this->upload($request, config('global.paths.product'));
        }            

        Product::updateItem($request, $id);
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
        Product::deleteItem($id);
        return redirect()->back()->with('message', __('message.delete_successful'));
    }

    public function release($id)
    {
        Product::releaseItem($id);
        return redirect()->back()->with('message', __('message.release_successful'));
    }    

    public function deactivate($id)
    {
        Product::deactivateItem($id);
        return redirect()->back()->with('message', __('message.deactivate_successful'));
    }
}
