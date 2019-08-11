<?php

namespace App\Http\Controllers;
use App\Stock;
use App\Product;
use App\StockMovementType;
use App\Contact;
use App\Location;
use App\StockMovement;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;

class StockController extends Controller
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
        if(!\Auth::user()->allowView(config('global.modules.quotation'))) {
            abort(401);
        }

        $search = Input::get ('search');
        $movement_type_id = Input::get ('movement_type_id');

        $types = StockMovementType::list();


        $stock_movements = StockMovement::where('is_delete', 0)
                                    ->where(function($data) use ($search) {
                                        $data->where('reference_code','LIKE','%'.$search.'%')
                                        ->orWhere('movement_date','LIKE','%'.$search.'%');
                                    });
                                    
        if($movement_type_id != null) {
            $stock_movements = $stock_movements->where('movement_type_id', $movement_type_id);    
        }

        $stock_movements = $stock_movements->orderBy('created_at', 'DESC')
                                ->paginate(config('global.page_limit'));        

        // dd($stock_movements[0]->location_name);

        return view('stocks.index', ['types' => $types, 'stock_movements' => $stock_movements]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // check the permission on the user profile
        if(!\Auth::user()->allowCreate(config('global.modules.stock'))) {
            abort(401);
        }

        $label = Input::get('label');
        $stock_movement_types = StockMovementType::listByLabel($label);
        $locations = Location::list();
        $contacts = Contact::list();
        $products = Product::storable($is_release = false);

        return view('stocks.create', [ 'types' => $stock_movement_types, 'contacts' => $contacts, 'locations' => $locations, 'products' => $products]);
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

            $result = StockMovement::createItem($request);
            foreach ($request->product_id_array as $key => $value) {                
                $stock = new Stock();
                $stock->stock_movement_id = $result->id;
                $stock->product_id = $request->product_id_array[$key];
                $stock->initial_qty = $request->initial_qty_array[$key];
                $stock->save();
            }

        } catch(\Exception $e)
        {
            DB::rollback();
            throw $e;
        }

        DB::commit();                         
        return response()->json([ 'stock' => $result, 'status' => 'success']);   
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($product_id)
    {
        // check the permission on the user profile
        if(!\Auth::user()->allowView(config('global.modules.stock'))) {
            abort(401);
        }
        $remark = 'stock_out';
        $product = Product::findOrFail($product_id);
        $stocks = Stock::listByProductId($product_id);
        // $count = 

        // dd($count);

        return view('stocks.show', ['stocks' => $stocks, 'product' => $product]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // check the permission on the user profile
        if(!\Auth::user()->allowEdit(config('global.modules.stock'))) {
            abort(401);
        }

        $stock_movement = StockMovement::findOrFail($id);
        $stock_movement_types = StockMovementType::listByLabel($stock_movement->remark);
        $locations = Location::list();
        $contacts = Contact::list();
        $products = Product::storable();

        return view('stocks.update', [ 'types' => $stock_movement_types, 'contacts' => $contacts, 'locations' => $locations, 'products' => $products, 'stock_movement' => $stock_movement]);
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
        // Start transaction!
        DB::beginTransaction();

        try {

            StockMovement::updateItem($request, $id);
            Stock::where('stock_movement_id', $id)->delete();
            foreach ($request->product_id_array as $key => $value) {                
                $stock = new Stock();
                $stock->stock_movement_id = $id;
                $stock->product_id = $request->product_id_array[$key];
                $stock->initial_qty = $request->initial_qty_array[$key];
                $stock->save();
            }

        } catch(\Exception $e)
        {
            DB::rollback();
            throw $e;
        }

        DB::commit();                         
        return response()->json(['status' => 'success']);   

        // return response()->json([ 'data' => $request->all(), 'status' => 'success']);   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        StockMovement::deleteItem($id);
        return redirect()->back()->with('message', __('message.delete_successful'));
    }

    public function done(Request $request, $id)
    {
        StockMovement::doneItem($id);
        return redirect()->back()->with('message', __('message.update_successful'));
    }

    public function generateRow($product_id, $qty) {
        $product = Product::findOrFail($product_id);
        return view('stocks.row', ['product' => $product, 'qty' => $qty]);
    }
}
