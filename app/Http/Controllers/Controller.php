<?php

namespace App\Http\Controllers;
use App\ProductVariant;
use App\CurrencyExchangeRate;
use App\ProductPrice;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function phoneFormat($telephone) {

        $str = substr($telephone, -1);
        if($str == "_") {
            $telephone = rtrim($telephone, "_");
        }

        return $telephone;
    }

    public function exchangeRate($calculation, $price, $rate) {

        switch ($calculation) {
            case 'multiplication':
                return $price = $price * $rate;        
            case 'divide':
                return $price = $price / $rate;
            default:
                return $price;
        }
    }

    function calculatePrice($customer_group_id, $product_id, $qty, $variant_id_array, $currency) 
    {
        // get extra price
        $extra_price = 0;      
        
        foreach ($variant_id_array as $key => $value) {
            $item = ProductVariant::where('id', $value)->first();
            if(!empty($item)) {
                $extra_price += $item->extra_price;
            }
        }

        // collect data        
        $exchange_rate = CurrencyExchangeRate::currentRate($currency->id)->rate;
        $base_price = ProductPrice::price($customer_group_id, $product_id, $qty);        
        // calculation
        $price = $base_price + $extra_price;
        // exchange currency
        return $this->exchangeRate($currency->calculation, $price, $exchange_rate);        
    }    

    public function upload($request, $directory) {
        
        $file_path = "";
        switch ($directory) {
            case config('global.paths.root'):
                $file_path = $directory."logo-placeholder.png";
                break;            
            case config('global.paths.product'):
                $file_path = $directory."no-image-placeholder.jpg";
                break;            
        	case config('global.paths.contact'):
        		$file_path = $directory."contact-placeholder.jpg";
        		break;
        	case config('global.paths.category'):
                $file_path = $directory."no-image-placeholder.jpg";
                break;
        	default:
        		return "";
        }
             
        if ($request->hasFile('file_upload')) {
            $file_path = $directory.time().'.'.request()->file_upload->getClientOriginalExtension();
            request()->file_upload->move(public_path($directory), $file_path);
        }       	

        return $file_path;
    }
}
