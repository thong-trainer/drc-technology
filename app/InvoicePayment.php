<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoicePayment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'invoice_id', 'payment_date', 'amount', 'payment_method', 'received_by', 'notes', 'description', 'created_by', 'updated_by'
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

    public static function createItem($request, $invoice_id) {
        $item = new self;
        $item->create($request->all() + [
        	'received_by' => \Auth::user()->id,
        	'invoice_id' => $invoice_id,
        ]);
    }    
}
