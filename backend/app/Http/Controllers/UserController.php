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
    public function assignSucursales(Request $request)
    {
        DB::transaction(function () use($request) {
            $user = \App\Models\User::find($request->user_id);
            $user->branches()->detach();
            foreach ($request->selectedOrganizationBranch as $item) {
                $user->branches()->attach($item['id']); 
            }
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
}
