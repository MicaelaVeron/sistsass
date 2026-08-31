<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
class UserController extends Controller
{
    public function index()
    {
        return \App\Models\User::all();
    }
    public function store(Request $request)
    {
        DB::transaction(function () use($request) {
            $request->validate([
                'name' => 'required|string',
                'email' => 'required',
                'password' => 'required',
            ]);
            $user = \App\Models\User::where('email', $request->email)->first();    
            if ($user) {
                throw ValidationException::withMessages([
                    'user' => ['El usuario ingresado, ya existe'],
                ]);
            }
            \App\Models\User::createOrUpdate($request);
            return response()->json(['message' => 'Procesado Exitosamente'], 200);
        });
    }
    public function edit($id)
    {
        $user = \App\Models\User::find($id);
        return $user;
    }
    public function update(Request $request, string $id)
    {
        DB::transaction(function () use($request) {
            $request->validate([
                'name' => 'required|string',
                'email' => 'required',
            ]);
            $user = \App\Models\User::where('email', $request->email)->first();    
            
            if ($user && $user->id != $request->id) {
                throw ValidationException::withMessages([
                    'user' => ['El usuario ingresado, ya existe'],
                ]);
            }
            \App\Models\User::createOrUpdate($request);
            return response()->json(['message' => 'Procesado Exitosamente'], 200);
        });
    }
    public function destroy($id)
    {
        $user = \App\Models\User::find($id);
        $user->delete();
        return response()->json(['message' => 'Usuario eliminado correctamente'], 200);
    }
    public function assignOrganizationsAndRoles(Request $request)
    {
        DB::transaction(function () use($request) {
            $request->validate([
                'user_id' => 'required',
                'selectedOrganizationRol' => 'required',
            ]);
            $organizatonRol = \App\Models\OrganizationRolUser::createOrUpdate($request);
            return response()->json(['message' => 'Procesado Exitosamente'], 200);
        });
    }
    public function assignBranches(Request $request)
    {
        DB::transaction(function () use($request) {
            $request->validate([
                'user_id' => 'required',
                'selectedBranch' => 'required',
            ]);
            $organizatonBranch = \App\Models\BranchUser::createOrUpdate($request);
            return response()->json(['message' => 'Procesado Exitosamente'], 200);
        });
    }
    public function getOrganizationAndAssignedRoles($id)
    {
        $assignedOrganizationRol = \App\Models\User::getUserOrganizationAndRoles($id);
        return $assignedOrganizationRol;
    }
    public function getOrganizationAssigned($id)
    {
        $assignedOrganization = \App\Models\User::getUserOrganizations($id);
        return $assignedOrganization;
    }
    public function getBranchAssigned($id)
    {
        $assignedBranch = \App\Models\User::getUserBranch($id);
        $assignedBranch = $assignedBranch->pluck('id');
        return $assignedBranch;
    }
    public function getBranchesWithUserOrganization($user_id)
    {
        $branches = \App\Models\User::getBranchesWithOrganization($user_id);
        return $branches;
    }
    public function inactive($id)
    {
        $user = \App\Models\User::find($id);
       
        if (count($user->organization_rol_users()->get())>0) {
             throw ValidationException::withMessages([
                'desactivar' => ['El usuario ya se encuentra asignado a una organización'],
            ]);
        }
        $user->status = 'inactive';
        $user->user_updated = auth()->user()->id;
        $user->save();
        return response()->json(['message' => 'Usuario desactivado correctamente'], 200);
    }
    public function active($id)
    {
        $user = \App\Models\User::find($id);
        $user->status = 'active';
        $user->user_updated = auth()->user()->id;
        $user->save();
        return response()->json(['message' => 'Usuario activado correctamente'], 200);
    }
     public function getOrganizationAndAssignedRolesForOrganization($user_id,$organization_id)
    {
        $assignedOrganizationRol = \App\Models\User::getUserOrganizationAndRolesForOrganization($user_id,$organization_id);
        return $assignedOrganizationRol;
    }
}
