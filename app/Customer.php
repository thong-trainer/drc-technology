<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'type'
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

    public function group(){
        return $this->belongsTo(CustomerGroup::class, 'group_id');
    } 

    public function contact(){
        return $this->belongsTo(Contact::class, 'contact_id');
    } 

    public function company(){
        return $this->belongsTo(Company::class, 'company_id');
    }     

    public static function list() {
        return self::select('id', 'code', 'contact_id', 'group_id')->with(['contact' => function($query) {
                        $query->addSelect('id', 'contact_name', 'primary_telephone');
                    }])->get();
    }    
}
