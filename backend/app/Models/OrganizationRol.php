<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
class OrganizationRol extends Model
{
    use SoftDeletes;
    protected $table = 'organization_rol';
    protected $primaryKey = 'id';
    protected $fillable = [
        'organization_id','rol_id','user_created','user_updated'
    ];
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }
    public static function createOrUpdate($request)
    {
        $organizationRol = OrganizationRol::where('rol_id',$request->rol_id)->where('organization_id',$request->organization_id)->first();
        if ($organizationRol) {
            $organizationRol->user_updated = auth()->user()->id;    
             
        } else {  
            $organizationRol = new OrganizationRol;
            $organizationRol->organization_id = $request->organization_id;
            $organizationRol->rol_id = $request->rol_id;
            $organizationRol->user_created = auth()->user()->id;
            $organizationRol->user_updated = auth()->user()->id;
        }
        $organizationRol->save();
        return $organizationRol;
    }
    public static function getRoles($organization_id)
    {
        $organizationRol = OrganizationRol::where('organization_id',$organization_id)->pluck('rol_id')->toArray();
        $roles = \App\Models\Rol::whereIn('id',$organizationRol)->get();
        return $roles;
    }
}
