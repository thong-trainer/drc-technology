<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DimensionDetail extends Model
{
    public function dimension(){
        return $this->belongsTo(Dimension::class, 'dimension_id');
    }     
}
