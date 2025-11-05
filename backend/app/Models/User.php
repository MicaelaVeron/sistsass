<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens,HasFactory, Notifiable,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'user_created',
        'user_updated',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public static function createOrUpdate($request)
    {
        if ($request->id != null) {
            $user = self::find($request->id);  
            $user->user_updated = auth()->user()->id; 
            if (!$user) {
                $user = new User;
                $user->email_verified_at = now();
                $user->remember_token = Str::random(10);
                $user->status = 'active';
                $user->user_created = auth()->user()->id;
                $user->user_updated = auth()->user()->id;
            }
        } else {  
            $user = new User;
            $user->email_verified_at = now();
            $user->remember_token = Str::random(10);
            $user->status = 'active';
            $user->user_created = auth()->user()->id;
            $user->user_updated = auth()->user()->id;

        }
        $user->password = $request->password ? Hash::make($request->password) : $user->password;
        $user->name = $request->name;
        $user->email = $request->email;
       
        $user->save();
    }
    public function organization_rol_users()
    {
        return $this->hasMany(OrganizationRolUser::class, 'user_id');
    }
    public static function getUserOrganizationAndRoles($id)
    {
        $user = self::find($id);
        $assignedOrganizationRol = $user->organization_rol_users()->pluck('organization_rol_id')->toArray();
        $assignedOrganizationRol = \App\Models\OrganizationRol::with('organization','rol')
        ->whereIn('id', $assignedOrganizationRol)
        ->get();
        return $assignedOrganizationRol;
    }
    public static function getUserOrganizations($id)
    {
        $user = self::find($id);
        $assignedOrganization = $user->organization_rol_users()->pluck('organization_rol_id')->toArray();
        $assignedOrganization = \App\Models\Organization::whereIn('id', $assignedOrganization)
        ->get();
        return $assignedOrganization;
    }
}
