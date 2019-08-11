<?php

namespace App;
// use App\Dimension;
use Illuminate\Database\Eloquent\Model;

class DimensionGroup extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dimension_group', 'description', 'created_by', 'updated_by'
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

    public function dimensions()
    {
    	return $this->belongsToMany(Dimension::class, 'dimension_details', 'group_id', 'dimension_id');
    }

    public function details()
    {
        return $this->hasMany(DimensionDetail::class,'group_id', 'id');
    }    

    public static function list() {
        return self::where('is_delete', 0)->get();
    }    

    public static function createItem($request) {
        $item = new self;
        $item->create($request->all())->dimensions()->sync($request->dimensions);
    }

    public static function updateItem($request, $id) {
        $item = self::findOrFail($id);    
        $item->update($request->all());
        $item->dimensions()->sync($request->dimensions);        
    }    

    public static function deleteItem($id) {
        $item = self::findOrFail($id);
        $item->is_delete = 1;
        $item->save();       
    }        



        
}
