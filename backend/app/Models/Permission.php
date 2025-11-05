<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
class Permission extends Model
{
    protected $fillable = [
        'name',
        'guard_name',
        'user_created',
        'user_updated',
    ];
    public static function createOrUpdate($request)
    {
        if ($request->id != null) {
            $permission = self::find($request->id);  
            $permission->user_updated = auth()->user()->id; 
            if (!$permission) {
                $permission = new Permission;
                $permission->user_created = auth()->user()->id;
                $permission->user_updated = auth()->user()->id;
            }
        } else {  
            $permission = new Permission;
            $permission->user_created = auth()->user()->id;
            $permission->user_updated = auth()->user()->id;

        }
        
        $permission->name = $request->name;
        $permission->guard_name = $request->guard_name;
       
        $permission->save();
    }
}
