<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
	public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id', 'dimension_id', 'value', 'description'
    ];

    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    } 

    public function dimension(){
        return $this->belongsTo(Dimension::class, 'dimension_id');
    } 

    public static function listByProductId($id) {
        return self::where('product_id', $id)->orderBy('dimension_id')->get();
    }

    public static function insertItems($data, $product_id) {
        $item = self::insert($data);
    }

    public static function updatePrice($id, $price) {
        $item = self::findOrFail($id);
        $item->extra_price = $price;
        $item->save();
    }    



}
