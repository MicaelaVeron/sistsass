<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrganizationController;
Route::get('/api/example', function () {
    return response()->json(['message' => 'Hello from Laravel API']);
});
Route::get('/sanctum/csrf-cookie', function () {
    return response()->json(['message' => 'CSRF cookie set']);
});
