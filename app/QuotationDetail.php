<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuotationDetail extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quotation_id', 'product_id', 'product_name', 'unit_price', 'qty', 'tax', 'discount', 'sub_total', 'notes'
    ];

    public static function createItems($data) {
        self::create($data);
    }
}
