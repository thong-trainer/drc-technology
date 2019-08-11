<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    public function module(){
        return $this->belongsTo(Module::class, 'module_id');
    }    
}
