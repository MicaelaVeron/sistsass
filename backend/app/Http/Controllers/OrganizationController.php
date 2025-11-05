<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return \App\Models\Organization::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::transaction(function () use($request) {
            $request->validate([
                'name' => 'required|string',
                'ruc' => 'required',
            ]);
            $organization = \App\Models\Organization::where('ruc', $request->url)->first();    
            if ($organization && $organization->id != $request->id) {
                // Si existe un menu con la misma url, pero no es el mismo que se está
                throw ValidationException::withMessages([
                    'url' => ['La organizacion ingresada, ya existe'],
                ]);
            }
            \App\Models\Organization::createOrUpdate($request);
            return response()->json(['message' => 'Procesado Exitosamente'], 200);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return \App\Models\Organization::find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $organization = \App\Models\Organization::find($id);
        $organization->delete();
        return response()->json(['message' => 'Organizacion eliminada correctamente'], 200);
    }
        public function downloadLogo($filename)
    {
        // Si viene como logos/archivo.png → lo resolvemos
        $filePath = storage_path('app/public/' . $filename);

        if (!file_exists($filePath)) {
            return response()->json(['error' => 'Archivo no encontrado'], 404);
        }

        $downloadName = basename($filePath);

        return response()->download($filePath, $downloadName);
    }
    
        public function getMenuOrganizationRol($rolId,$organizationId)
    {
        $organizationRol = \App\Models\OrganizationRol::where('rol_id',$rolId)->where('organization_id',$organizationId)->first();
        if ($organizationRol) {
            $menuOrganizationRol = \App\Models\MenuOrganizationRol::where('organization_rol_id',$organizationRol->id)->get();
            $menuIds = $menuOrganizationRol->pluck('menu_id')->toArray();
            
            $menus = \App\Models\Menu::with('children')
            ->whereIn('id', $menuIds)
            ->orderBy('order', 'asc')
            ->get();
            return response()->json($menus);
        }else{
            $menus = [];
            return response()->json($menus);
        }
        
    }
    public function getOrganizationRolPermission($rolId,$organizationId)
    {   
        $organizationRol = \App\Models\OrganizationRol::where('rol_id',$rolId)->where('organization_id',$organizationId)->first();
       // $organizationPermission = \App\Models\OrganizationRolPermission::where('organization_rol_id',$organizationRol->id)->where('organization_id',$organizationId)->first();
        if ($organizationRol) {
            $organizationPermission = \App\Models\OrganizationRolPermission::where('organization_rol_id',$organizationRol->id)->get();
            $permissionIds = $organizationPermission->pluck('permission_id')->toArray();
            
            $permissions = \App\Models\Permission::whereIn('id', $permissionIds)
            ->get();
            return response()->json($permissions);
        }else{
            $permissions = [];
            return response()->json($permissions);
        }
        
    }
    public function sendConfiguration(Request $request)
    {
        DB::transaction(function () use($request) {
            $request->validate([
                'rol_id' => 'required',
                'organization_id' => 'required',
                'selectedMenus' => 'required|array',
                'selectedPermissions' => 'required|array',

            ]);     
            $organizationRol = \App\Models\OrganizationRol::createOrUpdate($request);
            $menuOrganizationRol = \App\Models\MenuOrganizationRol::createOrUpdate($request->selectedMenus,$organizationRol->id);
            $organizationRolPermission = \App\Models\OrganizationRolPermission::createOrUpdate($request->selectedPermissions,$organizationRol->id);
            return response()->json(['message' => 'Procesado Exitosamente'], 200);
        });
    }
    

}
