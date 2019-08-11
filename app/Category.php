<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_name', 'parent_id', 'description', 'image_url', 'created_by', 'updated_by'
    ];

    public static function boot() {
        parent::boot();

        static::creating(function (Category $item) {
            $item->created_by = \Auth::user()->id;
        });

        static::updating(function (Category $item) {
            $item->updated_by = \Auth::user()->id;
        });        
    }    

    public function parent(){
        return $this->belongsTo(Category::class, 'parent_id');
    } 

    public static function list() {
    	return self::where('is_delete', 0)->get();
    }

    public static function parentList() {
    	return self::where('is_delete', 0)
                    ->where('parent_id', 0)
                    ->orderBy('category_name')
                    ->get();
    }    

    public static function createItem($request) {
        $item = new self;
        $item->create($request->all() + ['parent_id' => $request->parent]);
    }

    public static function updateItem($request, $id) {
        $item = self::findOrFail($id);    
        $item->update($request->all()  + ['parent_id' => $request->parent]);     
    }    

    public static function deleteItem($id) {
        $item = self::findOrFail($id);
        $item->is_delete = 1;
        $item->save();       
    }        


}