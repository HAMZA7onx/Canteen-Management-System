<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserRolePermissionController;
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

            // User Role and Permission routes
            Route::get('/{user}/roles', [UserRolePermissionController::class, 'getUserRoles']);
            Route::post('/{user}/roles/{roleId}', [UserRolePermissionController::class, 'assignRole']);
            Route::delete('/{user}/roles/{roleId}', [UserRolePermissionController::class, 'removeRole']);
            Route::get('/{user}/permissions', [UserRolePermissionController::class, 'getUserPermissions']);
            Route::post('/{user}/permissions/{permissionId}', [UserRolePermissionController::class, 'assignPermission']);
            Route::delete('/{user}/permissions/{permissionId}', [UserRolePermissionController::class, 'removePermission']);

        });

        Route::prefix('roles')->group(function () {
            Route::get('/', [RoleController::class, 'index']);
            Route::post('/', [RoleController::class, 'store']);
            Route::get('/{role}', [RoleController::class, 'show']);
            Route::put('/{role}', [RoleController::class, 'update']);
            Route::delete('/{role}', [RoleController::class, 'destroy']);

            // Get permissions assigned to a role
            Route::get('/{role}/permissions', [RoleController::class, 'getRolePermissions']);

            // Assign permissions to a role
            Route::post('/{role}/permissions/{permissionId}', [RoleController::class, 'assignPermission']);
            Route::delete('/{role}/permissions/{permissionId}', [RoleController::class, 'removePermission']);
        });

        Route::prefix('permissions')->group(function () {
            Route::get('/', [RoleController::class, 'indexPermissions']);
            Route::post('/', [RoleController::class, 'storePermission']);
            Route::get('/{permission}', [RoleController::class, 'showPermission']);
            Route::put('/{permission}', [RoleController::class, 'updatePermission']);
            Route::delete('/{permission}', [RoleController::class, 'destroyPermission']);
        });
    });
});


