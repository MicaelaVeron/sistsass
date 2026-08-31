<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
class BranchUser extends Model
{
    use SoftDeletes;
    protected $table = 'branches_users';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id','branch_id','user_created','user_updated'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public static function createOrUpdate($request)
    {
        $branchId = $request->selectedBranch;
        $userId = $request->user_id;
         // Si viene vacío: eliminar todos los registros
         if (empty($branchId)) {
            BranchUser::where('user_id', $userId)
                ->update(['user_updated' => Auth::id()]); // registrar quién eliminó

                BranchUser::where('user_id', $userId)->delete();
            return;
        }

        // Filtrar IDs válidos (por si llegan 0s o strings vacíos)
        $branchId = array_filter($branchId, fn($id) => is_numeric($id) && $id > 0);

        // Obtener actuales de la DB
        $branchExistentes = BranchUser::where('user_id', $userId)
            ->pluck('branch_id')
            ->toArray();

        $branchAAgregar = array_diff($branchId, $branchExistentes);
        $branchAEliminar = array_diff($branchExistentes, $branchId);

        // Agregar nuevos
        foreach ($branchAAgregar as $branch) {
            BranchUser::create([
                'branch_id' => $branch,
                'user_id' => $userId,
                'user_created' => Auth::id(),
                'user_updated' => Auth::id(),
            ]);
        }

        // Actualizar y eliminar los que sobran
        if (!empty($branchAEliminar)) {
            BranchUser::where('user_id', $userId)
            ->whereIn('branch_id', $branchAEliminar)
            ->update(['user_updated' => Auth::id()]);

            BranchUser::where('user_id', $userId)
            ->whereIn('branch_id', $branchAEliminar)
            ->delete();
        }
    }
}
