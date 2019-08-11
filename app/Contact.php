<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{

    public static function list() {
    	return self::select('id', 'contact_name', 'primary_telephone')->where('is_delete', 0)->get();
    }    	
}
