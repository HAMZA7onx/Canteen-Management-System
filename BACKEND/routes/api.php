<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminRolePermissionController;
use App\Http\Controllers\User\UserCategoryController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\CategoryDiscountController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\Role\PermissionController;
use App\Http\Controllers\Badge\BadgeController;
use App\Http\Controllers\Meal\MealMenuController;
use App\Http\Controllers\Meal\MealScheduleController;
use App\Http\Controllers\Meal\MealRecordController;

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Managing Collaborators
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::get('/{id}', [UserController::class, 'getUserDetails']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
    });

    // Managing Admins
    Route::prefix('admins')->group(function () {
        Route::get('/', [AdminController::class, 'index']);
        Route::post('/', [AdminController::class, 'store']);
        Route::get('/{id}', [AdminController::class, 'show']);
        Route::put('/{id}', [AdminController::class, 'update']);
        Route::delete('/{id}', [AdminController::class, 'destroy']);

        // Admin Roles routes
        Route::get('/{admin}/roles', [AdminRolePermissionController::class, 'getAdminRoles']);
        Route::post('/{admin}/roles/{roleId}', [AdminRolePermissionController::class, 'assignRole']);
        Route::delete('/{admin}/roles/{roleId}', [AdminRolePermissionController::class, 'removeRole']);
        Route::get('/{admin}/permissions', [AdminRolePermissionController::class, 'getAdminPermissions']);
        Route::post('/{admin}/permissions/{permissionId}', [AdminRolePermissionController::class, 'assignPermission']);
        Route::delete('/{admin}/permissions/{permissionId}', [AdminRolePermissionController::class, 'removePermission']);
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

    Route::prefix('meal-menus')->group(function () {
        Route::get('/', [MealMenuController::class, 'index']);
        Route::post('/', [MealMenuController::class, 'store']);
        Route::get('/{id}', [MealMenuController::class, 'show']);
        Route::put('/{id}', [MealMenuController::class, 'update']);
        Route::delete('/{id}', [MealMenuController::class, 'destroy']);
    });

    Route::prefix('meal-schedules')->group(function () {
        Route::get('/', [MealScheduleController::class, 'index']);
        Route::post('/', [MealScheduleController::class, 'store']);
        Route::get('/{id}', [MealScheduleController::class, 'show']);
        Route::put('/{id}', [MealScheduleController::class, 'update']);
        Route::delete('/{id}', [MealScheduleController::class, 'destroy']);
    });

    Route::prefix('category-discounts')->group(function () {
        Route::get('/', [CategoryDiscountController::class, 'index']);
        Route::post('/', [CategoryDiscountController::class, 'store']);
        Route::get('/{categoryDiscount}', [CategoryDiscountController::class, 'show']);
        Route::put('/{categoryDiscount}', [CategoryDiscountController::class, 'update']);
        Route::delete('/{categoryDiscount}', [CategoryDiscountController::class, 'destroy']);
    });

    Route::prefix('meal-records')->group(function () {
        Route::get('/', [MealRecordController::class, 'index']);
        Route::post('/', [MealRecordController::class, 'store']);
        Route::get('/{id}', [MealRecordController::class, 'show']);
        Route::put('/{id}', [MealRecordController::class, 'update']);
        Route::delete('/{id}', [MealRecordController::class, 'destroy']);
    });
});
