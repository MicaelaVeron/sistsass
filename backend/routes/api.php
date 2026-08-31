<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrganizationRolController;
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/user-context', [AuthController::class, 'updateContext']);
});
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/menu-index', [MenuController::class, 'index']);
    Route::post('/menu-store', [MenuController::class, 'store']);
    Route::get('/menu-edit/{id}', [MenuController::class, 'edit']);
    Route::get('/menu-destroy/{id}', [MenuController::class, 'destroy']);
    Route::get('/menu-getMenusForOrganizationRol/{organization_rol_id}', [MenuController::class, 'getMenusForOrganizationRol']);

    Route::get('/organization-index', [OrganizationController::class, 'index']);
    Route::post('/organization-store', [OrganizationController::class, 'store']);
    Route::get('/organization-edit/{id}', [OrganizationController::class, 'edit']);
    Route::get('/organization-destroy/{id}', [OrganizationController::class, 'destroy']);
    Route::get('/logos/download/{filename}', [OrganizationController::class, 'downloadLogo'])
        ->where('filename', '.*');
    Route::get('/organization-getMenuOrganizationRol/{rol_id}/{organization_id}', [OrganizationController::class, 'getMenuOrganizationRol']);
    Route::get('/organization-getOrganizationRolPermission/{rol_id}/{organization_id}', [OrganizationController::class, 'getOrganizationRolPermission']);
    Route::post('/organization-sendConfig', [OrganizationController::class, 'sendConfiguration']);
    Route::get('/organization-rol-getRolWithOrganization/{organization_id}', [OrganizationRolController::class, 'getRolWithOrganization']);

    Route::get('/branch-index', [BranchController::class, 'index']);
    Route::post('/branch-store', [BranchController::class, 'store']);
    Route::get('/branch-edit/{id}', [BranchController::class, 'edit']);
    Route::get('/branch-destroy/{id}', [BranchController::class, 'destroy']);
    Route::get('/branch-getBranchesWithOrganization/{organization_id}', [BranchController::class, 'getBranchesWithOrganization']);

    Route::get('/rol-index', [RolController::class, 'index']);
    Route::post('/rol-store', [RolController::class, 'store']);
    Route::get('/rol-edit/{id}', [RolController::class, 'edit']);
    Route::put('/rol-update/{id}', [RolController::class, 'update']);
    Route::get('/rol-destroy/{id}', [RolController::class, 'destroy']);

    Route::get('/permission-index', [PermissionController::class, 'index']);
    Route::post('/permission-store', [PermissionController::class, 'store']);
    Route::get('/permission-edit/{id}', [PermissionController::class, 'edit']);
    Route::put('/permission-update/{id}', [PermissionController::class, 'update']);
    Route::get('/permission-destroy/{id}', [PermissionController::class, 'destroy']);

    Route::get('/user-index', [UserController::class, 'index']);
    Route::post('/user-store', [UserController::class, 'store']);
    Route::get('/user-edit/{id}', [UserController::class, 'edit']);
    Route::put('/user-update/{id}', [UserController::class, 'update']);
    Route::get('/user-destroy/{id}', [UserController::class, 'destroy']);
    Route::post('/user-assignOrganizationsAndRoles', [UserController::class, 'assignOrganizationsAndRoles']);
    Route::post('/user-assignBranches', [UserController::class, 'assignBranches']);
    Route::get('/user-getOrganizationAndAssignedRoles/{id}', [UserController::class, 'getOrganizationAndAssignedRoles']);
    Route::get('/user-getOrganizationAssigned/{id}', [UserController::class, 'getOrganizationAssigned']);
    Route::get('/user-getBranchAssigned/{id}', [UserController::class, 'getBranchAssigned']);
    Route::get('/user-getBranchesWithUserOrganization/{id}', [UserController::class, 'getBranchesWithUserOrganization']);
    Route::get('/user-inactive/{id}', [UserController::class, 'inactive']);
    Route::get('/user-active/{id}', [UserController::class, 'active']);
    Route::get('/user-getOrganizationAndAssignedRolesForOrganization/{user_id}/{organization_id}', [UserController::class, 'getOrganizationAndAssignedRolesForOrganization']);

});