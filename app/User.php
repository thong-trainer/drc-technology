<?php

namespace App;

use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'gender', 'telephone', 'image_url', 'role_id', 'location_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function boot() {
        parent::boot();

        static::creating(function (User $user) {
            $user->password = Hash::make(config('global.default_password'));
        });
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role(){
        return $this->belongsTo(Role::class, 'role_id');
    }    

    public static function defaultCurrency() {
        return Currency::where('is_default', 1)->first();
    }

    public static function companyInfo() {
        return Company::where('is_enable', 0)->first();
    }

    public static function allowDeliveryMethod() {
        $item = Setting::where('section', '=', 'sale')
                        ->where('label', '=', 'delivery_methods')
                        ->first();

        if(!empty($item) && $item->input_value == '1') {
            return true;
        }

        return false;
    }     

    public static function allowVariant() {
        $item = Setting::where('section', '=', 'product')
                        ->where('label', '=', 'variant_and_dimension')
                        ->first();

        if(!empty($item) && $item->input_value == '1') {
            return true;
        }

        return false;
    }    

    public static function allowPriceList() {
        $item = Setting::where('section', '=', 'product')
                        ->where('label', '=', 'price_list')
                        ->first();

        if(!empty($item) && $item->input_value == '1') {
            return true;
        }

        return false;
    }       

    public static function allowStock() {
        $item = Setting::where('section', '=', 'product')
                        ->where('label', '=', 'stock')
                        ->first();

        if(!empty($item) && $item->input_value == '1') {
            return true;
        }

        return false;
    } 

    public static function allowMultiStorageLocations() {
        $item = Setting::where('section', '=', 'company')
                        ->where('label', '=', 'multiple_locations')
                        ->first();

        if(!empty($item) && $item->input_value == '1') {
            return true;
        }

        return false;
    }                

    public static function createRecord($request) {
        // copy a image to 'uploads' folder in 'public' folder
        $path = config('global.paths.user');
        $file_path = $path."user-placeholder.jpg";        
        if ($request->hasFile('file_upload')) {
            $file_path = $path.time().'.'.request()->file_upload->getClientOriginalExtension();
            request()->file_upload->move(public_path($path), $file_path);
        }        

        // saving to the database
        $item = new self;
        $item->create($request->all() + [
            'role_id' => $request->role,
            'image_url' => $file_path,
        ]);      

        return $item;
    }

    public static function updateRecord($request, $id) {

        $item = User::findOrFail($id);

        $file_url = $item->image_url;
        // copy a image to 'uploads' folder in 'public' folder
        if ($request->hasFile('file_upload')) {
            $path = config('global.paths.user');
            $file_url = $path.time().'.'.request()->file_upload->getClientOriginalExtension();
            request()->file_upload->move(public_path($path), $file_url);

            $item->image_url =  $file_url;
        }    
        
        $item->update($request->all() + [
            'role_id' => $request->role,
            'image_url' => $file_url,
        ]); 


        // updating to the database
        // $item->name = $request->name;
        // $item->telephone = $request->telephone;
        // $item->gender = $request->gender;
        // $item->role_id = $request->role;
        // $item->save();

    }

    public static function allowView($module) {

        $moduels = Module::get();
        foreach ($moduels as $key => $item) {
            if($item->module_name === $module) {
                $permission = Permission::where('role_id', \Auth::user()->role_id)
                                        ->where('module_id', $item->id)
                                        ->first();

                if(!empty($permission) && $permission->is_view == 1) {
                    return true;    
                }                                
            }
        }

        return false;
    }    

    public static function allowCreate($module) {

        $moduels = Module::get();
        foreach ($moduels as $key => $item) {
            if($item->module_name === $module) {
                $permission = Permission::where('role_id', \Auth::user()->role_id)
                                        ->where('module_id', $item->id)
                                        ->first();

                if(!empty($permission) && $permission->is_create == 1) {
                    return true;    
                }                                
            }
        }

        return false;
    }

    public static function allowEdit($module) {

        $moduels = Module::get();
        foreach ($moduels as $key => $item) {
            if($item->module_name === $module) {
                $permission = Permission::where('role_id', \Auth::user()->role_id)
                                        ->where('module_id', $item->id)
                                        ->first();

                if(!empty($permission) && $permission->is_edit == 1) {
                    return true;    
                }                                
            }
        }

        return false;
    }

    public static function allowDelete($module) {

        $moduels = Module::get();
        foreach ($moduels as $key => $item) {
            if($item->module_name === $module) {
                $permission = Permission::where('role_id', \Auth::user()->role_id)
                                        ->where('module_id', $item->id)
                                        ->first();

                if(!empty($permission) && $permission->is_delete == 1) {
                    return true;    
                }                                
            }
        }

        return false;
    }     

    public static function allowImport($module) {

        $moduels = Module::get();
        foreach ($moduels as $key => $item) {
            if($item->module_name === $module) {
                $permission = Permission::where('role_id', \Auth::user()->role_id)
                                        ->where('module_id', $item->id)
                                        ->first();

                if(!empty($permission) && $permission->is_delete == 1) {
                    return true;    
                }                                
            }
        }

        return false;
    }     

    public static function allowExport($module) {

        $moduels = Module::get();
        foreach ($moduels as $key => $item) {
            if($item->module_name === $module) {
                $permission = Permission::where('role_id', \Auth::user()->role_id)
                                        ->where('module_id', $item->id)
                                        ->first();

                if(!empty($permission) && $permission->is_export == 1) {
                    return true;    
                }                                
            }
        }

        return false;
    }                    
}
