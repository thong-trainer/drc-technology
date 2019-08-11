<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'location_name', 'description', 'created_by', 'updated_by'
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

    public static function list(){
        return self::where('is_delete', 0)->get();
    } 

}
