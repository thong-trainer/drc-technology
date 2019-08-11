<?php

namespace App\Http\Controllers;
use App\Product;
use App\Customer;
use App\Supplier;
use App\user;
use Illuminate\Http\Request;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $product_count = Product::where('is_delete', 0)->count();
        $customer_count = Customer::where('is_delete', 0)->count();
        $supplier_count = Supplier::where('is_delete', 0)->count();
        $user_count = User::where('is_delete', 0)->where('is_hide', 0)->count();
        return view('home', [
                                'product_count' => $product_count, 
                                'customer_count' => $customer_count, 
                                'supplier_count' => $supplier_count, 
                                'user_count' => $user_count
                            ]);
    }
}
