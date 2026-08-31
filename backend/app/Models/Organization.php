<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
class Organization extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'ruc', 'address', 'telephone', 'email','logo', 'active','user_created','user_updated'
    ];
    public static function createOrUpdate($request)
    {
        if (!$request->id) {
            $organization = self::find($request->id);  
            $organization->user_updated = auth()->user()->id;
            if (!$organization) {
                $organization = new Organization;
                $organization->active = true;
                $organization->user_created = auth()->user()->id;
                $organization->user_updated = auth()->user()->id;
            }
             
        } else {  
            $organization = new Organization;
            $organization->active = true;
            $organization->user_created = auth()->user()->id;
            $organization->user_updated = auth()->user()->id;
        }
        
        $storage_url='';
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public'); // logos/archivo.png
            $storage_url = $path;  
        }
        $organization->name = $request->name;
        $organization->ruc = $request->ruc;
        $organization->address = $request->address;
        $organization->telephone = $request->telephone;
        $organization->email =  $request->email;
        $organization->logo = ($organization->logo) ? $organization->logo : $storage_url;
        $organization->save();
    }
    public function organization_rol()
    {
        return $this->hasMany(OrganizationRol::class, 'organization_id');
    }

}
