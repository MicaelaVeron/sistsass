<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return \App\Models\Menu::with('parent','children')->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::transaction(function () use($request) {
            $request->validate([
                'name' => 'required|string',
                //'url' => 'required',
            ]);
            
            $menu = \App\Models\Menu::where('url', $request->url)->first();    
            if ($menu && $menu->id != $request->id) {
                // Si existe un menu con la misma url, pero no es el mismo que se está
                throw ValidationException::withMessages([
                    'url' => ['La url ingresada, ya existe'],
                ]);
            }
            \App\Models\Menu::createOrUpdateMenu($request);
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
    public function edit($id)
    {
        $menu = \App\Models\Menu::find($id);
        return $menu;
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
        $menu = \App\Models\Menu::find($id);
        if($menu->children()->count() > 0) {
            return response()->json(['message' => 'No se puede eliminar un menú que tiene hijos'], 400);
        }
        $menu->delete();
        return response()->json(['message' => 'Menú eliminado correctamente'], 200);
    }
}
