<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
class OrganizationRolUser extends Model
{
    use SoftDeletes;
    protected $table = 'organization_rol_user';
    protected $primaryKey = 'id';
    protected $fillable = [
        'organization_rol_id','user_id','user_created','user_updated'
    ];
    public function organization_rol()
    {
        return $this->belongsTo(OrganizationRol::class, 'organization_rol_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public static function createOrUpdate($request)
    {
        $organizationRolId = [];
        $userId = $request->user_id;
        if (!empty($request->selectedOrganizationRol)) {
            foreach ($request->selectedOrganizationRol as $key => $value) {
                $organizationRolId[] = \App\Models\OrganizationRol::where('organization_id',$value['organization_id'])->where('rol_id',$value['rol_id'])->first()->id;      
            }     
        }
         // Si viene vacío: eliminar todos los registros
         if (empty($organizationRolId)) {
            OrganizationRolUser::where('user_id', $userId)
                ->update(['user_updated' => Auth::id()]); // registrar quién eliminó

                OrganizationRolUser::where('user_id', $userId)->delete();
            return;
        }

        // Filtrar IDs válidos (por si llegan 0s o strings vacíos)
        $organizationRolId = array_filter($organizationRolId, fn($id) => is_numeric($id) && $id > 0);

        // Obtener actuales de la DB
        $organizationRolExistentes = OrganizationRolUser::where('user_id', $userId)
            ->pluck('organization_rol_id')
            ->toArray();

        $organizationRolAAgregar = array_diff($organizationRolId, $organizationRolExistentes);
        $organizationRolAEliminar = array_diff($organizationRolExistentes, $organizationRolId);

        // Agregar nuevos
        foreach ($organizationRolAAgregar as $organization_rol_id) {
            OrganizationRolUser::create([
                'organization_rol_id' => $organization_rol_id,
                'user_id' => $userId,
                'user_created' => Auth::id(),
                'user_updated' => Auth::id(),
            ]);
        }

        // Actualizar y eliminar los que sobran
        if (!empty($organizationRolAEliminar)) {
            OrganizationRolUser::where('user_id', $userId)
            ->whereIn('organization_rol_id', $organizationRolAEliminar)
            ->update(['user_updated' => Auth::id()]);

            OrganizationRolUser::where('user_id', $userId)
            ->whereIn('organization_rol_id', $organizationRolAEliminar)
            ->delete();
        }
    }
}
