<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckUserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');

Route::middleware('auth:sanctum')->group(function () {
    Route::middleware('role:super admin')->group(function () {

        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'index']);
            Route::post('/', [UserController::class, 'store']);
            Route::get('/{id}', [UserController::class, 'getUserDetails']);
            Route::put('/{id}', [UserController::class, 'update']);
            Route::delete('/{id}', [UserController::class, 'destroy']);
            Route::get('/getUserRolesNames/{id}', [UserController::class, 'getUserRolesNames']);
        });

        Route::prefix('role')->group(function () {
            Route::get('/getRolesNames', [RoleController::class, 'getRolesNames']);

            Route::post('/storeRole', [RoleController::class, 'storeRole']);
            Route::put('/updateRole/{id}', [RoleController::class, 'updateRole']);
            Route::delete('/deleteRole/{id}', [RoleController::class, 'deleteRole']);

            Route::post('/giveRoleToUser/{roleId}/{userId}', [RoleController::class, 'giveRoleToUser']);
            Route::put('/updateRoleOfUser/{id}', [RoleController::class, 'updateRoleOfUser']);
            Route::delete('/deleteRoleFromUser/{role_id}/{user_id}', [RoleController::class, 'deleteRoleFromUser']);
        });

        Route::prefix('permission')->group(function () {
            Route::post('/storePermission', [RoleController::class, 'storePermission']);
            Route::put('/updatePermission/{id}', [RoleController::class, 'updatePermission']);
            Route::delete('/deletePermission/{id}', [RoleController::class, 'deletePermission']);

            Route::post('/givePermissionToRole', [RoleController::class, 'givePermissionToRole']);
            Route::put('/updatePermissionOfRole/{id}', [RoleController::class, 'updatePermissionOfRole']);
            Route::delete('/deletePermissionFromRole/{id}', [RoleController::class, 'deletePermissionFromRole']);
        });
    });
});


