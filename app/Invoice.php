<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'invoice_no', 'quotation_id', 'customer_id', 'salesperson_id', 'issue_date', 'due_date', 'payment_term_id', 'notes', 'amount', 'tax', 'discount', 'discount_amount', 'grand_total', 'rate', 'created_by', 'updated_by'
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

    public function salesperson(){
        return $this->belongsTo(User::class, 'salesperson_id');
    }     

    public function paymentTerm(){
        return $this->belongsTo(PaymentTerm::class, 'payment_term_id');
    }     

    public function currency(){
        return $this->belongsTo(Currency::class, 'currency_id');
    } 

    public function details()
    {
        return $this->hasMany(InvoiceDetail::class, 'invoice_id');
    }  

    public function payments()
    {
        return $this->hasMany(InvoicePayment::class, 'invoice_id');
    }             

    public static function statusColor($status) {
        switch ($status) {
            case config('global.invoice_status.issued'):
                return "label label-warning";
            case config('global.invoice_status.paid'):
                return "label label-success";                            
            case config('global.invoice_status.deleted'):
                return "label label-danger";   
        }
    }
}
