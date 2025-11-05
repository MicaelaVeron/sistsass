<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
class RolController extends Controller
{
    public function index()
    {
        return \App\Models\Rol::all();
    }
    public function store(Request $request)
    {
        DB::transaction(function () use($request) {
            $request->validate([
                'name' => 'required|string',
                'guard_name' => 'required',
            ]);
            $rol = \App\Models\Rol::where('name', $request->name)->where('guard_name', $request->guard_name)->first();    
            if ($rol) {
                throw ValidationException::withMessages([
                    'rol' => ['El rol ingresado, ya existe'],
                ]);
            }
            \App\Models\Rol::createOrUpdate($request);
            return response()->json(['message' => 'Procesado Exitosamente'], 200);
        });
    }
    public function edit($id)
    {
        $rol = \App\Models\Rol::find($id);
        return $rol;
    }
    public function update(Request $request, string $id)
    {
        DB::transaction(function () use($request) {
            $request->validate([
                'name' => 'required|string',
                'guard_name' => 'required',
            ]);
            $rol = \App\Models\Rol::where('name', $request->name)->where('guard_name', $request->guard_name)->first();    
            
            if ($rol && $rol->id != $request->id) {
                throw ValidationException::withMessages([
                    'rol' => ['El rol ingresado, ya existe'],
                ]);
            }
            \App\Models\Rol::createOrUpdate($request);
            return response()->json(['message' => 'Procesado Exitosamente'], 200);
        });
    }
    public function destroy($id)
    {
        $rol = \App\Models\Rol::find($id);
        $rol->delete();
        return response()->json(['message' => 'Rol eliminado correctamente'], 200);
    }
}
