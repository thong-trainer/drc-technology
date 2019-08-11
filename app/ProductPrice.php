<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id', 'customer_group_id', 'minimum_qty', 'price', 'start_date', 'end_date', 'created_by', 'updated_by'
    ];

    public static function boot() {
        parent::boot();

        static::creating(function (self $item) {
            $item->created_by = \Auth::user()->id;
        });

        static::updating(function (self $item) {
            $item->updated_by = \Auth::user()->id;
        });        
    }

    public function customerGroup(){
        return $this->belongsTo(CustomerGroup::class, 'customer_group_id');
    } 

    public static function defaultPrice($product_id, $customer_group_id) {
        $price_cusotmer_group = self::where('is_delete', 0)
                                    ->where('product_id', $product_id)
                                    ->where('minimum_qty', 1)    
                                    ->where('customer_group_id', $customer_group_id)
                                    ->first();
        if($price_cusotmer_group) {
            return $price_cusotmer_group->price;    
        } else {
            return self::where('is_delete', 0)
                        ->where('is_default', 1)
                        ->first()->price;
        }
    }

    public static function price($customer_group_id, $product_id, $qty) {
        $today = \Carbon\Carbon::now();
        $item = self::where('is_delete', 0)
                        ->where('product_id', $product_id)
                        ->where('customer_group_id', $customer_group_id)
                        ->where('minimum_qty', '<=', $qty)
                        ->orderBy('minimum_qty', 'DESC')
                        ->first();
        if(empty($item)) {
            return self::defaultPrice($product_id, $customer_group_id);
        }

        return $item->price;
    }

    public static function createDefault($product_id, $price) {

        $item = new self;
        $item->product_id = $product_id;
        $item->customer_group_id = CustomerGroup::default()->id;
        $item->price = $price;
        $item->start_date = \Carbon\Carbon::now();
        $item->is_default = 1;
        $item->save();
    }

    public static function createItem($request) {
        $item = new self;
        $item->create($request->all());
    }

    public static function updateItem($request, $id) {
        $item = self::findOrFail($id);    
        $item->update($request->all());     
    }    

    public static function deleteItem($id) {
        $item = self::findOrFail($id);
        $item->is_delete = 1;
        $item->save();    
    }         

}
