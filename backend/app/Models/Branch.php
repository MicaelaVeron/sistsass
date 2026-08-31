<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
class Branch extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'number', 'address', 'telephone','organization_id','user_created','user_updated'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
    public function branches_users()
    {
        return $this->hasMany(BranchUser::class,'branch_id');
    }
    public static function createOrUpdate($request)
    {
        if (!$request->id) {
            $branch = self::find($request->id); 
            $branch->user_updated = auth()->user()->id;  
            if (!$branch) {
                $branch = new Branch;
                $branch->user_created = auth()->user()->id;
                $branch->user_updated = auth()->user()->id;
            }
        } else {  
            $branch = new Branch;
            $branch->user_created = auth()->user()->id;
            $branch->user_updated = auth()->user()->id;
        }
        
        $branch->name = $request->name;
        $branch->number = $request->number;
        $branch->address = $request->address;
        $branch->telephone = $request->telephone;
        $branch->organization_id = $request->organization_id;
        $branch->save();
    }
    public static function getBranches($organization_id=null)
    {
     if ($organization_id) {
        $branches = Branch::where('organization_id',$organization_id)->get();
        return $branches;
     } else {
        $branches = Branch::all();
        return $branches;
     }
    }
}
