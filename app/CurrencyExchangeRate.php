<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CurrencyExchangeRate extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'currency_id', 'rate', 'applied_date', 'created_by', 'updated_by'
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

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }    

    public static function currentRate($currency_id) {
        return self::where('currency_id', $currency_id)->first();
    }

}
