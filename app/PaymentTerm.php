<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentTerm extends Model
{
    public static function list() {
    	return self::where('is_delete', 0)->orderBy('is_default', 'DESC')->get();
    }
}
