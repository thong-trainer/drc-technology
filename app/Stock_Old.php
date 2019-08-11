<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock_Old extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'location_id', 'product_id', 'qty', 'limit', 'value', 'status', 'created_by', 'updated_by'
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

    public function location(){
        return $this->belongsTo(Location::class, 'location_id');
    } 

    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    }     

    public static function listByProductId($product_id) {
        return self::where('product_id', $product_id)->paginate(config('global.page_limit'));
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
