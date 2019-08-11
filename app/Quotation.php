<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'quotation_number', 'customer_id', 'quotation_date', 'validity_date', 'payment_term_id', 'notes', 'amount', 'tax', 'discount', 'discount_amount', 'grand_total', 'rate', 'created_by', 'updated_by'
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

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id');
    } 

    public function user(){
        return $this->belongsTo(User::class, 'created_by');
    } 

    public function paymentTerm(){
        return $this->belongsTo(PaymentTerm::class, 'payment_term_id');
    }     

    public function currency(){
        return $this->belongsTo(Currency::class, 'currency_id');
    } 

    public function details()
    {
        return $this->hasMany(QuotationDetail::class, 'quotation_id');
    }  

    public function invoice(){
        return $this->hasOne(Invoice::class, 'quotation_id');
    } 


    public static function statusColor($status) {
        switch ($status) {
            case config('global.quotation_status.pending'):
                return "label label-default";
            case config('global.quotation_status.confirmed'):
                return "label label-warning";
            case config('global.quotation_status.invoiced'):
                return "label label-success";                            
            case config('global.quotation_status.deleted'):
                return "label label-danger";   
        }
    }

    // public static function list() {
    // 	return self::where('is_delete', 0)->get();
    // }

    public static function deleteItem($id) {
        $item = self::findOrFail($id);
        $item->is_delete = 1;
        $item->status = config('global.quotation_status.deleted');
        $item->save();       
    }     

    public static function confirmItem($id) {
        $item = self::findOrFail($id);
        $item->status = config('global.quotation_status.confirmed');
        $item->confirm_date = \Carbon\Carbon::now();
        $item->is_confirm = 1;
        $item->save();       
    }    

    public static function invoiceCreated($id) {
        $item = self::findOrFail($id);
        $item->status = config('global.quotation_status.invoiced');
        $item->confirm_date = \Carbon\Carbon::now();
        $item->is_confirm = 1;
        $item->save();       
    }                         
}
