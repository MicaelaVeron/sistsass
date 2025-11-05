<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
class BranchController extends Controller
{
    public function index()
    {
        return \App\Models\Branch::with('organization')->get();
    }
    public function store(Request $request)
    {
        DB::transaction(function () use($request) {
            $request->validate([
                'name' => 'required|string',
                'number' => 'required',
                'organization_id' => 'nullable|exists:organizations,id',
            ]);
            $branch = \App\Models\Branch::where('number', $request->number)->where('organization_id',$request->organization_id)->first();    
            if ($branch && $branch->id != $request->id) {
                // Si existe un menu con la misma url, pero no es el mismo que se está
                throw ValidationException::withMessages([
                    'number' => ['La sucursal ingresada, ya existe'],
                ]);
            }
            \App\Models\Branch::createOrUpdate($request);
            return response()->json(['message' => 'Procesado Exitosamente'], 200);
        });
    }
    public function edit($id)
    {
        $branch = \App\Models\Branch::find($id);
        return $branch;
    }
    public function destroy($id)
    {
        $branch = \App\Models\Branch::find($id);
        $branch->delete();
        return response()->json(['message' => 'Sucursal eliminada correctamente'], 200);
    }
    public function getBranchesWithOrganization($organization_id)
    {
        $branches = \App\Models\Branch::getBranches($organization_id);
        return $branches;
    }
}
