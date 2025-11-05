<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
class MenuOrganizationRol extends Model
{
    use SoftDeletes;
    protected $table = 'menu_organization_rol';
    protected $primaryKey = 'id';
    protected $fillable = [
        'organization_rol_id','menu_id','user_created','user_updated'
    ];
    public function organization_rol()
    {
        return $this->belongsTo(OrganizationRol::class, 'organization_rol_id');
    }
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
    public static function createOrUpdate($menusId,$organizationRolId)
    {
         // Si viene vacío: eliminar todos los registros
         if (empty($menusId)) {
            MenuOrganizationRol::where('organization_rol_id', $organizationRolId)
                ->update(['user_updated' => Auth::id()]); // registrar quién eliminó

                MenuOrganizationRol::where('organization_rol_id', $organizationRolId)->delete();
            return;
        }

        // Filtrar IDs válidos (por si llegan 0s o strings vacíos)
        $menusId = array_filter($menusId, fn($id) => is_numeric($id) && $id > 0);

        // Obtener actuales de la DB
        $menusExistentes = MenuOrganizationRol::where('organization_rol_id', $organizationRolId)
            ->pluck('menu_id')
            ->toArray();

        $menusAAgregar = array_diff($menusId, $menusExistentes);
        $menusAEliminar = array_diff($menusExistentes, $menusId);

        // Agregar nuevos
        foreach ($menusAAgregar as $menu_id) {
            MenuOrganizationRol::create([
                'menu_id' => $menu_id,
                'organization_rol_id' => $organizationRolId,
                'user_created' => Auth::id(),
                'user_updated' => Auth::id(),
            ]);
        }

        // Actualizar y eliminar los que sobran
        if (!empty($menusAEliminar)) {
            MenuOrganizationRol::where('organization_rol_id', $organizationRolId)
                ->whereIn('menu_id', $menusAEliminar)
                ->update(['user_updated' => Auth::id()]);

                MenuOrganizationRol::where('organization_rol_id', $organizationRolId)
                ->whereIn('menu_id', $menusAEliminar)
                ->delete();
        }
    }
}
