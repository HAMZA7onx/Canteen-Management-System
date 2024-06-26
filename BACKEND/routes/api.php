<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminRolePermissionController;
use App\Http\Controllers\User\UserCategoryController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\Role\PermissionController;
use App\Http\Controllers\Badge\BadgeController;
use App\Http\Controllers\WeekSchedule\WeekScheduleController;
use App\Http\Controllers\WeekSchedule\DailyMealController;
use App\Http\Controllers\WeekSchedule\MenuController;
use App\Http\Controllers\WeekSchedule\FoodComposantsController;


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

        Route::post('/import', [BadgeController::class, 'importRfids']);

        Route::get('/users/all-rfids-lost', [BadgeController::class, 'getUsersWithAllRfidsLost']);
        Route::get('/users/without-rfids', [BadgeController::class, 'getUsersWithoutRfids']);

        Route::put('/{badgeId}/status', [BadgeController::class, 'updateBadgeStatus']);
        Route::put('/{badgeId}/assign', [BadgeController::class, 'assignRfidToUser']);
    });

    Route::prefix('week-schedules')->group(function () {
        Route::get('/', [WeekScheduleController::class, 'index']);
        Route::post('/', [WeekScheduleController::class, 'store']);
        Route::get('/{weekSchedule}', [WeekScheduleController::class, 'show']);
        Route::put('/{weekSchedule}', [WeekScheduleController::class, 'update']);
        Route::delete('/{weekSchedule}', [WeekScheduleController::class, 'destroy']);

        Route::post('/{weekSchedule}/daily-meals/{day}', [WeekScheduleController::class, 'attachDailyMeal']);
        Route::delete('/{weekSchedule}/daily-meals/{dailyMeal}/{day}', [WeekScheduleController::class, 'detachDailyMeal']);
    });

    Route::prefix('daily-meals')->group(function () {
        Route::get('/', [DailyMealController::class, 'index']);
        Route::post('/', [DailyMealController::class, 'store']);
        Route::get('/{dailyMeal}', [DailyMealController::class, 'show']);
        Route::put('/{dailyMeal}', [DailyMealController::class, 'update']);
        Route::delete('/{dailyMeal}', [DailyMealController::class, 'destroy']);

        // Attach a menu to a daily meal
        Route::post('/{dailyMeal}/menus/{menuId}', [DailyMealController::class, 'attachMenu']);
        // Detach a menu from a daily meal
        Route::delete('/{dailyMeal}/menus/{menu}', [DailyMealController::class, 'detachMenu']);
    });

    Route::prefix('menus')->group(function () {
        Route::get('/', [MenuController::class, 'index']);
        Route::post('/', [MenuController::class, 'store']);
        Route::get('/{menu}', [MenuController::class, 'show']);
        Route::put('/{menu}', [MenuController::class, 'update']);
        Route::delete('/{menu}', [MenuController::class, 'destroy']);

        Route::post('/{menu}/food-composants/{foodComposantId}', [MenuController::class, 'attachFoodComposant']);
        Route::delete('/{menu}/food-composants/{foodComposant}', [MenuController::class, 'detachFoodComposant']);
    });

    Route::prefix('food-composants')->group(function () {
        Route::get('/', [FoodComposantsController::class, 'index']);
        Route::post('/', [FoodComposantsController::class, 'store']);
        Route::get('/{foodComposant}', [FoodComposantsController::class, 'show']);
        Route::put('/{foodComposant}', [FoodComposantsController::class, 'update']);
        Route::delete('/{foodComposant}', [FoodComposantsController::class, 'destroy']);
    });
});
