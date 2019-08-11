<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'stock_movement_id', 'product_id', 'initial_qty', 'done_qty', 'notes'
    ];

    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    } 


    public function stockMovement(){
        return $this->belongsTo(StockMovement::class, 'stock_movement_id');
    } 


    public static function listByProductId($product_id) {
        return self::where('product_id', $product_id)->paginate(config('global.page_limit'));
    }

}
