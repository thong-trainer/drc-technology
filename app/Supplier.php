<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    public function contact(){
        return $this->belongsTo(Contact::class, 'contact_id');
    } 

    public function company(){
        return $this->belongsTo(Company::class, 'company_id');
    }   

    public static function list() {
        return self::where('is_delete', 0)->get();
    }      
}
