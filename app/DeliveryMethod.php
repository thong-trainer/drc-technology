<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryMethod extends Model
{
    public static function list() {
        return self::where('is_delete', 0)->get();
    }    
}
