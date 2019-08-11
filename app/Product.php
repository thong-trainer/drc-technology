<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_name', 'product_type', 'barcode', 'ref_number', 'customer_tax', 'cost', 'description', 'image_url', 'dimension_group_id', 'category_id', 'sale_unit_id', 'purchase_unit_id', 'is_pos', 'is_release', 'created_by', 'updated_by'
    ];

    public function prices(){
        return $this->hasMany(ProductPrice::class, 'product_id')->where('is_delete', 0);
    } 

    public function stockIn() {
        return $this->hasMany(Stock::class, 'product_id')
                            ->whereHas('stockMovement', function($data) {
                                $data->where('remark', '=', config('global.stock_status.stock_in'));
                            })->sum('initial_qty');
    }    

    public function stockOut() {
        return $this->hasMany(Stock::class, 'product_id')
                            ->whereHas('stockMovement', function($data) {
                                $data->where('remark', '=', config('global.stock_status.stock_out'));
                            })->sum('initial_qty');
    }    

    public function onHand() {
        return self::stockIn() - self::stockOut();
    } 

    public function sold() {

        return $this->hasMany(Stock::class, 'product_id')
                            ->whereHas('stockMovement', function($data) {
                                $data->where('remark', '=', config('global.stock_status.stock_out'))
                                    ->where('movement_type_id', StockMovementType::saleOrder()->id);
                            })->sum('initial_qty');
    } 

    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    } 

    public function dimensionGroup(){
        return $this->belongsTo(DimensionGroup::class, 'dimension_group_id');
    } 

    public function saleUnit(){
        return $this->belongsTo(Unit::class, 'sale_unit_id');
    } 

    public static function boot() {
        parent::boot();

        static::creating(function (self $item) {
            $item->created_by = \Auth::user()->id;
            $item->purchase_unit_id = $item->sale_unit_id;             
        });

        static::updating(function (self $item) {
            $item->updated_by = \Auth::user()->id;
            $item->purchase_unit_id = $item->sale_unit_id;                              
        });        
    }

    public function hasVariant(){
        $item = ProductVariant::where('product_id', $this->id)->first();        
        if(empty($item))
            return false;

        return true;
    } 

    public function salePrice(){
        return ProductPrice::where('product_id', $this->id)
                            ->where('is_delete', 0)
                            ->where('is_default', 1)
                            ->first()->price;
    }     

    public static function list() {
        return Product::where('is_delete', 0)->where('is_release', 1)->orderBy('product_name')->get();
    }

    public static function storable($is_release = true) {
        $items = Product::where('is_delete', 0)->where('product_type' ,'=', 'storable');

        if($is_release == true) {
           $items = $items->where('is_release', $is_release);
        }

        return $items->orderBy('product_name')->get();        
    }

    public static function service() {
        return Product::where('is_delete', 0)
                        ->where('is_release', 1)
                        ->where('product_type' ,'=', 'service')
                        ->orderBy('product_name')
                        ->get();
    }

    public static function createItem($request) {
        if(empty($request->barcode)) {
            $request['barcode'] = config('global.codes.product');
        }    

        $item = self::create($request->all());

        if($item->barcode == $request['barcode']) {
        	$item->barcode = $item->barcode.$item->id;
        	$item->save();
        }      

        return $item;     
    }

    public static function updateItem($request, $id) {
        if(empty($request->barcode)) {
            $request['barcode'] = config('global.codes.product').$id;
        }

        $item = self::findOrFail($id);    
        $item->is_pos = $request->is_pos ? 1 : 0;
        $item->update($request->all());      
    }    

    public static function deleteItem($id) {
        $item = self::findOrFail($id);
        $item->is_delete = 1;
        $item->save();       
    }    

    public static function releaseItem($id) {
        $item = self::findOrFail($id);
        $item->is_release = 1;
        $item->save();       
    } 

    public static function deactivateItem($id) {
        $item = self::findOrFail($id);
        $item->is_release = 0;
        $item->save();       
    }          
}
