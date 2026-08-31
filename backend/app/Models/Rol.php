<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
class Rol extends Model
{
    use SoftDeletes;
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
    public function organization_rol()
    {
        return $this->hasMany(OrganizationRol::class, 'rol_id');
    }
}
