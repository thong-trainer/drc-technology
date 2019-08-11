<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'reference_code', 'source_document', 'movement_type_id', 'movement_date', 'contact_id', 'remark', 'location_id', 'status', 'notes', 'description', 'created_by', 'updated_by'
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

    public function details()
    {
        return $this->hasMany(Stock::class, 'stock_movement_id');
    }      

    public function movementType(){
        return $this->belongsTo(StockMovementType::class, 'movement_type_id');
    } 

    public function location(){
        return $this->belongsTo(Location::class, 'location_id');
    }

    public static function count($label) {
    	return self::where('remark', '=', $label)->count();
    }

    public static function statusColor($remark) {
        switch ($remark) {
            case config('global.stock_status.stock_in'):
                return "label label-success";
            case config('global.stock_status.stock_out'):
                return "label label-danger";
        }
    }

    public static function createItem($request) {
        $item = new self;
        $code = ($request->remark == config('global.stock_status.stock_in')) ? config('global.codes.stock_in') : config('global.codes.stock_out');
        
        return $item->create($request->all() + [
        	'reference_code' => $code. (self::count($request->remark) + 1000 + 1),
        	'movement_date' => \Carbon\Carbon::now(),
        	'status' => config('global.stock_movement_status.waiting')
        ]);
    }

    public static function createStockForSaleOrder($customer_id, $sale_order_no) {
        $contact_id = Customer::findOrFail($customer_id)->contact_id;
        $remark = config('global.stock_status.stock_out');
        $code = config('global.codes.stock_out');
        $item = new self;
        return $item->create([
            'contact_id' => $contact_id,
            'movement_type_id' => StockMovementType::saleOrder()->id,
            'remark' => $remark,
            'source_document' => $sale_order_no,
            'location_id' => \Auth::user()->location_id,
            'reference_code' => $code. (self::count($remark) + 1000 + 1),
            'movement_date' => \Carbon\Carbon::now(),
            'status' => config('global.stock_movement_status.done')
        ]);        
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

    public static function doneItem($id) {
        $item = self::findOrFail($id);
        $item->status = config('global.stock_movement_status.done');
        $item->save();       
    }      

}
