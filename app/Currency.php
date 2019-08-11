<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'currency', 'symbol', 'is_enable', 'description', 'created_by', 'updated_by'
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

    public function rates()
    {
        return $this->hasMany(CurrencyExchangeRate::class, 'currency_id');
    }    

    public static function default()
    {
        return self::where('is_default', 1)->first();
    }                 

    public static function list() {
        return self::where('is_enable', 1)
                    ->orderBy('is_default', 'DESC')
                    ->get();
    }

}
