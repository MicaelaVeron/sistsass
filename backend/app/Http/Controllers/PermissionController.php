<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
class PermissionController extends Controller
{
    public function index()
    {
        return \App\Models\Permission::all();
    }
    public function store(Request $request)
    {
        DB::transaction(function () use($request) {
            $request->validate([
                'name' => 'required|string',
                'guard_name' => 'required',
            ]);
            $permission = \App\Models\Permission::where('name', $request->name)->where('guard_name', $request->guard_name)->first();    
            if ($permission) {
                throw ValidationException::withMessages([
                    'permission' => ['El permiso ingresado, ya existe'],
                ]);
            }
            \App\Models\Permission::createOrUpdate($request);
            return response()->json(['message' => 'Procesado Exitosamente'], 200);
        });
    }
    public function edit($id)
    {
        $permission = \App\Models\Permission::find($id);
        return $permission;
    }
    public function update(Request $request, string $id)
    {
        DB::transaction(function () use($request) {
            $request->validate([
                'name' => 'required|string',
                'guard_name' => 'required',
            ]);
            $permission = \App\Models\Permission::where('name', $request->name)->where('guard_name', $request->guard_name)->first();    
            
            if ($permission && $permission->id != $request->id) {
                throw ValidationException::withMessages([
                    'permission' => ['El permiso ingresado, ya existe'],
                ]);
            }
            \App\Models\Permission::createOrUpdate($request);
            return response()->json(['message' => 'Procesado Exitosamente'], 200);
        });
    }
    public function destroy($id)
    {
        $permission = \App\Models\Permission::find($id);
        $permission->delete();
        return response()->json(['message' => 'Permisos eliminado correctamente'], 200);
    }
}
