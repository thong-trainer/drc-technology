<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockMovementType extends Model
{
  	public static function listByLabel($label) {
  		return self::where('is_enable', 1)->where('label', '=', $label)->get();
  	}

    public static function list() {
        return self::where('is_delete', 0)->get();
    }   

    public static function saleOrder() {
        return self::where('is_enable', 0)->where('is_delete', 0)->first();
    }   
}
