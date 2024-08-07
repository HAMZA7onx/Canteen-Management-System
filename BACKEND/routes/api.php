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
use App\Http\Controllers\Records\DailyRecordController;
use App\Http\Controllers\RecordsDashboardController;
use App\Http\Controllers\Badge\AdminBadgeController;
use App\Http\Controllers\PosDeviceController;
use App\Http\Controllers\User\CategoryDiscountController;
use App\Http\Controllers\Admin\AdminReportSubscriptionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DailyMealStatsController;
use App\Http\Controllers\LogoController;

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login-with-badge', [AuthController::class, 'loginWithBadge']);
Route::post('/register', [AuthController::class, 'register']);

Route::post('/upload-logo', [LogoController::class, 'upload']);
Route::get('/get-logo', [LogoController::class, 'get']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Managing Collaborators
    Route::prefix('users')->middleware('check.permission:voir_collaborateurs')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store'])->middleware('check.permission:creer_collaborateurs');
        Route::get('/{id}', [UserController::class, 'getUserDetails']);
        Route::put('/{id}', [UserController::class, 'update'])->middleware('check.permission:modifier_collaborateurs');
        Route::delete('/{id}', [UserController::class, 'destroy'])->middleware('check.permission:supprimer_collaborateurs');
        Route::post('/import', [UserController::class, 'import'])->middleware('check.permission:importer_collaborateurs');
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

//    Route::prefix('roles')->middleware('check.permission:voir_roles')->group(function () {
//        Route::get('/', [RoleController::class, 'index']);
//        Route::post('/', [RoleController::class, 'store'])->middleware('check.permission:creer_roles');
//        Route::get('/{role}', [RoleController::class, 'show']);
//        Route::put('/{role}', [RoleController::class, 'update'])->middleware('check.permission:modifier_roles');
//        Route::delete('/{role}', [RoleController::class, 'destroy'])->middleware('check.permission:supprimer_roles');
//
//        // Get permissions assigned to a role
//        Route::get('/{role}/permissions', [RoleController::class, 'getRolePermissions']);
//
//        // Assign permissions to a role
//        Route::post('/{role}/permissions/{permissionId}', [RoleController::class, 'assignPermission'])->middleware('check.permission:assigner_permission_a_roles');
//        Route::delete('/{role}/permissions/{permissionId}', [RoleController::class, 'removePermission'])->middleware('check.permission:desassigner_permission_des_roles');
//    });
    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index']);
        Route::post('/', [RoleController::class, 'store']);
        Route::get('/{role}', [RoleController::class, 'show']);
        Route::put('/{role}', [RoleController::class, 'update']);
        Route::delete('/{role}', [RoleController::class, 'destroy']);

        // Get permissions assigned to a role
        Route::get('/{role}/permissions', [RoleController::class, 'getRolePermissions']);

        // Assign permissions to a role
        Route::post('/{role}/permissions', [RoleController::class, 'assignPermissions']);

        Route::delete('/{role}/permissions/{permissionId}', [RoleController::class, 'removePermission']);
    });

    Route::prefix('permissions')->group(function () {
        Route::get('/refresh-permissions', [PermissionController::class, 'refreshPermissions']);
        Route::get('/', [PermissionController::class, 'index']);
        Route::post('/', [PermissionController::class, 'store']);
        Route::get('/{permission}', [PermissionController::class, 'show']);
        Route::put('/{permission}', [PermissionController::class, 'update']);
        Route::delete('/{permission}', [PermissionController::class, 'destroy']);
    });

    Route::prefix('user-categories')->middleware('check.permission:voir_categorie_de_collaborateur')->group(function () {
        Route::get('/', [UserCategoryController::class, 'index']);
        Route::post('/', [UserCategoryController::class, 'store'])->middleware('check.permission:creer_categorie_de_collaborateur');
        Route::get('/{id}', [UserCategoryController::class, 'show']);
        Route::put('/{id}', [UserCategoryController::class, 'update'])->middleware('check.permission:modifier_categorie_de_collaborateur');
        Route::delete('/{id}', [UserCategoryController::class, 'destroy'])->middleware('check.permission:supprimer_categorie_de_collaborateur');
    });

    Route::prefix('badges')->middleware('check.permission:voir_badges_collaborateurs')->group(function () {
        Route::get('/', [BadgeController::class, 'index']);
        Route::post('/', [BadgeController::class, 'store']);
        Route::get('/{id}', [BadgeController::class, 'show']);
        Route::put('/{id}', [BadgeController::class, 'update']);
        Route::delete('/{id}', [BadgeController::class, 'destroy']);

        Route::post('/import', [BadgeController::class, 'importRfids'])->middleware('check.permission:importer_badges_collaborateurs');

        Route::get('/users/all-rfids-lost', [BadgeController::class, 'getUsersWithAllRfidsLost']);
        Route::get('/users/without-rfids', [BadgeController::class, 'getUsersWithoutRfids']);

        Route::put('/{badgeId}/status', [BadgeController::class, 'updateBadgeStatus'])->middleware('check.permission:gerer_badges_collaborateurs');
        Route::put('/{badgeId}/assign', [BadgeController::class, 'assignRfidToUser'])->middleware('check.permission:gerer_badges_collaborateurs');
    });

    Route::prefix('week-schedules')->middleware('check.permission:voir_profils_repas')->group(function () {
        Route::get('/', [WeekScheduleController::class, 'index']);
        Route::post('/', [WeekScheduleController::class, 'store'])->middleware('check.permission:creer_profils_repas');
        Route::get('/{weekSchedule}', [WeekScheduleController::class, 'show']);
        Route::put('/{weekSchedule}', [WeekScheduleController::class, 'update'])->middleware('check.permission:modifier_profils_repas');
        Route::delete('/{weekSchedule}', [WeekScheduleController::class, 'destroy'])->middleware('check.permission:supprimer_profils_repas');
        Route::post('/{weekSchedule}/daily-meals/{day}', [WeekScheduleController::class, 'attachDailyMeal'])->middleware('check.permission:assigner_repas');
        Route::delete('/{weekSchedule}/daily-meals/{dailyMeal}/{day}', [WeekScheduleController::class, 'detachDailyMeal'])->middleware('check.permission:desassigner_repas');
        Route::get('/{weekSchedule}/daily-meals/{day}/{dailyMeal}/discounts', [WeekScheduleController::class, 'getDailyMealDiscounts']);
    });

    Route::prefix('daily-meals')->middleware('check.permission:voir_repas')->group(function () {
        Route::get('/', [DailyMealController::class, 'index']);
        Route::post('/', [DailyMealController::class, 'store'])->middleware('check.permission:creer_repas');
        Route::get('/{dailyMeal}', [DailyMealController::class, 'show']);
        Route::put('/{dailyMeal}', [DailyMealController::class, 'update'])->middleware('check.permission:modifier_repas');
        Route::delete('/{dailyMeal}', [DailyMealController::class, 'destroy'])->middleware('check.permission:supprimer_repas');

        // Attach a menu to a daily meal
        Route::post('/{dailyMeal}/menus', [DailyMealController::class, 'attachMenus'])->middleware('check.permission:assigner_categories_menus');

        // Detach a menu from a daily meal
        Route::delete('/{dailyMeal}/menus/{menu}', [DailyMealController::class, 'detachMenu'])->middleware('check.permission:desassigner_categories_menus');
    });

    Route::prefix('menus')->middleware('check.permission:voir_categories_menus')->group(function () {
        Route::get('/', [MenuController::class, 'index']);
        Route::post('/', [MenuController::class, 'store'])->middleware('check.permission:creer_categories_menus');
        Route::get('/{menu}', [MenuController::class, 'show']);
        Route::put('/{menu}', [MenuController::class, 'update'])->middleware('check.permission:modifier_categories_menus');
        Route::delete('/{menu}', [MenuController::class, 'destroy'])->middleware('check.permission:supprimer_categories_menus');

        Route::post('/{menu}/food-composants', [MenuController::class, 'attachFoodComposants'])->middleware('check.permission:assigner_composants_menus');
        Route::delete('/{menu}/food-composants/{foodComposant}', [MenuController::class, 'detachFoodComposant'])->middleware('check.permission:desassigner_composants_menus');
    });

    Route::prefix('food-composants')->middleware('check.permission:voir_composants_menus')->group(function () {
        Route::get('/', [FoodComposantsController::class, 'index']);
        Route::post('/', [FoodComposantsController::class, 'store'])->middleware('check.permission:creer_composants_menus');
        Route::get('/{foodComposant}', [FoodComposantsController::class, 'show']);
        Route::put('/{foodComposant}', [FoodComposantsController::class, 'update'])->middleware('check.permission:modifier_composants_menus');
        Route::delete('/{foodComposant}', [FoodComposantsController::class, 'destroy'])->middleware('check.permission:supprimer_composants_menus');
    });

    Route::prefix('badging')->group(function () {
        Route::get('/current-meal-count', [DailyRecordController::class, 'getCurrentMealBadgeCount']);
        Route::get('/current-meal', [DailyRecordController::class, 'getCurrentMeal']);
        Route::get('/{day}', [DailyRecordController::class, 'index']);
        Route::get('/{day}/{id}', [DailyRecordController::class, 'show']);
        Route::post('/{day}', [DailyRecordController::class, 'store']);
    });

    Route::prefix('records')->middleware('check.permission:voir_enregistrements_repas')->group(function () {
        Route::get('/years', [RecordsDashboardController::class, 'getYears']);
        Route::get('/{year}/months', [RecordsDashboardController::class, 'getMonths']);
        Route::get('/{year}/{month}/monthly-totals', [RecordsDashboardController::class, 'getMonthlyTotals']);
        Route::get('/{year}/{month}/days', [RecordsDashboardController::class, 'getDays']);
        Route::get('/{year}/{month}/{day}', [RecordsDashboardController::class, 'getDayRecords']);
        Route::get('/advanced', [RecordsDashboardController::class, 'getAdvancedRecords']);
    });

    Route::prefix('admins-badges')->middleware('check.permission:voir_badges_administrateurs')->group(function () {
        Route::get('/', [AdminBadgeController::class, 'index']);
        Route::post('/', [AdminBadgeController::class, 'store']);
        Route::get('/{id}', [AdminBadgeController::class, 'show']);
        Route::put('/{id}', [AdminBadgeController::class, 'update']);
        Route::delete('/{id}', [AdminBadgeController::class, 'destroy']);

        Route::post('/import', [AdminBadgeController::class, 'importRfids'])->middleware('check.permission:importer_badges_administrateurs');

        Route::get('/admins/all-rfids-lost', [AdminBadgeController::class, 'getUsersWithAllRfidsLost']);
        Route::get('/admins/without-rfids', [AdminBadgeController::class, 'getUsersWithoutRfids']);

        Route::put('/{badgeId}/status', [AdminBadgeController::class, 'updateBadgeStatus'])->middleware('check.permission:gerer_badges_administrateurs');
        Route::put('/{badgeId}/assign', [AdminBadgeController::class, 'assignRfidToUser'])->middleware('check.permission:gerer_badges_administrateurs');
    });

    Route::prefix('pos-devices')->middleware('check.permission:voir_POS')->middleware('auth:sanctum')->group(function () {
        Route::get('/', [PosDeviceController::class, 'index']);
        Route::post('/', [PosDeviceController::class, 'store'])->middleware('check.permission:creer_POS');
        Route::get('/{id}', [PosDeviceController::class, 'show']);
        Route::put('/{id}', [PosDeviceController::class, 'update'])->middleware('check.permission:modifier_POS');
        Route::delete('/{id}', [PosDeviceController::class, 'destroy'])->middleware('check.permission:supprimer_POS');
        Route::get('/getStatus', [PosDeviceController::class, 'getStatus']);
    });

    Route::get('discounts/{day}/{mealId}', [CategoryDiscountController::class, 'getDiscountsForMeal']);

    Route::prefix('admin-report-subscriptions')->middleware('check.permission:gerer_subscribtions_des_admin')->group(function () {
        Route::get('/', [AdminReportSubscriptionController::class, 'index']);
        Route::post('/', [AdminReportSubscriptionController::class, 'store']);
        Route::put('/{subscription}', [AdminReportSubscriptionController::class, 'update']);
        Route::delete('/{subscription}', [AdminReportSubscriptionController::class, 'destroy']);
    });

    Route::prefix('home')->group(function () {
        Route::get('/users', [HomeController::class, 'get_users']);
        Route::get('/menus', [HomeController::class, 'get_menus']);
        Route::get('/badges', [HomeController::class, 'get_badges']);
        Route::get('/week-schedules', [HomeController::class, 'get_week_schedules']);
    });

    Route::get('/daily-meal-stats', [DailyMealStatsController::class, 'getDailyStats']);
});
