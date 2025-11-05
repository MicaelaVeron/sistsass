<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
class Rol extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'guard_name',
        'user_created',
        'user_updated',
    ];
    public static function createOrUpdate($request)
    {
        if ($request->id != null) {
            $rol = self::find($request->id);  
            $rol->user_updated = auth()->user()->id; 
            if (!$rol) {
                $rol = new Rol;
                $rol->user_created = auth()->user()->id;
                $rol->user_updated = auth()->user()->id;
            }
        } else {  
            $rol = new Rol;
            $rol->user_created = auth()->user()->id;
            $rol->user_updated = auth()->user()->id;

        }
        
        $rol->name = $request->name;
        $rol->guard_name = $request->guard_name;
       
        $rol->save();
    }
}
