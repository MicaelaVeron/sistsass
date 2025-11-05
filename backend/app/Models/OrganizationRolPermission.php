<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
class OrganizationRolPermission extends Model
{
    use SoftDeletes;
    protected $table = 'organization_rol_permission';
    protected $primaryKey = 'id';
    protected $fillable = [
        'organization_rol_id','permission_id','user_created','user_updated'
    ];
    public function organization_rol()
    {
        return $this->belongsTo(OrganizationRol::class, 'organization_rol_id');
    }
    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }
    public static function createOrUpdate($permissionsId,$organizationRolId)
    {
         // Si viene vacío: eliminar todos los registros
         if (empty($permissionsId)) {
            OrganizationRolPermission::where('organization_rol_id', $organizationRolId)
                ->update(['user_updated' => Auth::id()]); // registrar quién eliminó

                OrganizationRolPermission::where('organization_rol_id', $organizationRolId)->delete();
            return;
        }

        // Filtrar IDs válidos (por si llegan 0s o strings vacíos)
        $permissionsId = array_filter($permissionsId, fn($id) => is_numeric($id) && $id > 0);

        // Obtener actuales de la DB
        $permissionExistentes = OrganizationRolPermission::where('organization_rol_id', $organizationRolId)
            ->pluck('permission_id')
            ->toArray();

        $permissionsAAgregar = array_diff($permissionsId, $permissionExistentes);
        $permissionsAEliminar = array_diff($permissionExistentes, $permissionsId);

        // Agregar nuevos
        foreach ($permissionsAAgregar as $permission_id) {
            OrganizationRolPermission::create([
                'organization_rol_id' => $organizationRolId,
                'permission_id' => $permission_id,
                'user_created' => Auth::id(),
                'user_updated' => Auth::id(),
            ]);
        }

        // Actualizar y eliminar los que sobran
        if (!empty($permissionsAEliminar)) {
            OrganizationRolPermission::where('organization_rol_id', $organizationRolId)
                ->whereIn('permission_id', $permissionsAEliminar)
                ->update(['user_updated' => Auth::id()]);

                OrganizationRolPermission::where('organization_rol_id', $organizationRolId)
                ->whereIn('permission_id', $permissionsAEliminar)
                ->delete();
        }
    }
}
