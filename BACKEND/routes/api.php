<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\User\UserCategoryController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserRolePermissionController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\Role\PermissionController;
use App\Http\Controllers\Badge\BadgeController;
use App\Http\Controllers\Meal\MealNameController;
use App\Http\Controllers\Meal\MealModeController;
use App\Http\Controllers\Meal\MealItemController;
use App\Http\Controllers\Meal\MealController;
use App\Http\Controllers\Meal\MealRecordController;

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

            // User Roles routes
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
            Route::get('/', [PermissionController::class, 'index']);
            Route::post('/', [PermissionController::class, 'store']);
            Route::get('/{permission}', [PermissionController::class, 'show']);
            Route::put('/{permission}', [PermissionController::class, 'update']);
            Route::delete('/{permission}', [PermissionController::class, 'destroy']);
        });
    });

    Route::prefix('admins')->group(function () {
        Route::get('/', [AdminController::class, 'index']);
        Route::post('/', [AdminController::class, 'store']);
        Route::get('/{id}', [AdminController::class, 'show']);
        Route::put('/{id}', [AdminController::class, 'update']);
        Route::delete('/{id}', [AdminController::class, 'destroy']);
    });

    Route::prefix('user-categories')->group(function () {
        Route::get('/', [UserCategoryController::class, 'index']);
        Route::post('/', [UserCategoryController::class, 'store']);
        Route::get('/{id}', [UserCategoryController::class, 'show']);
        Route::put('/{id}', [UserCategoryController::class, 'update']);
        Route::delete('/{id}', [UserCategoryController::class, 'destroy']);
    });

    Route::prefix('badges')->group(function () {
        Route::get('/', [BadgeController::class, 'index']);
        Route::post('/', [BadgeController::class, 'store']);
        Route::get('/{id}', [BadgeController::class, 'show']);
        Route::put('/{id}', [BadgeController::class, 'update']);
        Route::delete('/{id}', [BadgeController::class, 'destroy']);
    });

    Route::prefix('meal-names')->group(function () {
        Route::get('/', [MealNameController::class, 'index']);
        Route::post('/', [MealNameController::class, 'store']);
        Route::get('/{id}', [MealNameController::class, 'show']);
        Route::put('/{id}', [MealNameController::class, 'update']);
        Route::delete('/{id}', [MealNameController::class, 'destroy']);
    });

    Route::prefix('meal-modes')->group(function () {
        Route::get('/', [MealModeController::class, 'index']);
        Route::post('/', [MealModeController::class, 'store']);
        Route::get('/{id}', [MealModeController::class, 'show']);
        Route::put('/{id}', [MealModeController::class, 'update']);
        Route::delete('/{id}', [MealModeController::class, 'destroy']);
    });

    Route::prefix('meal-items')->group(function () {
        Route::get('/', [MealItemController::class, 'index']);
        Route::post('/', [MealItemController::class, 'store']);
        Route::get('/{id}', [MealItemController::class, 'show']);
        Route::put('/{id}', [MealItemController::class, 'update']);
        Route::delete('/{id}', [MealItemController::class, 'destroy']);
    });

    Route::prefix('meals')->group(function () {
        Route::get('/', [MealController::class, 'index']);
        Route::post('/', [MealController::class, 'store']);
        Route::get('/{id}', [MealController::class, 'show']);
        Route::put('/{id}', [MealController::class, 'update']);
        Route::delete('/{id}', [MealController::class, 'destroy']);
    });

    Route::prefix('meal-records')->group(function () {
        Route::get('/', [MealRecordController::class, 'index']);
        Route::post('/', [MealRecordController::class, 'store']);
        Route::get('/{id}', [MealRecordController::class, 'show']);
        Route::put('/{id}', [MealRecordController::class, 'update']);
        Route::delete('/{id}', [MealRecordController::class, 'destroy']);
    });
});
