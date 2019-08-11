<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'unit_name', 'description'
    ];

    public static function boot() {
        parent::boot();
    }    

    public static function list() {
    	return self::where('is_delete', 0)->get();
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
