<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class Seeder3_Permissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // User Permissions
        Permission::create(['name' => 'view_users', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'create_user', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'update_user', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'delete_user', 'guard_name' => 'sanctum']);

        // Admin Permissions
        Permission::create(['name' => 'view_admins', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'create_admin', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'update_admin', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'delete_admin', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'assign_role_to_admin', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'remove_role_from_admin', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'assign_permission_to_admin', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'remove_permission_from_admin', 'guard_name' => 'sanctum']);

        // Role Permissions
        Permission::create(['name' => 'view_roles', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'create_role', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'update_role', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'delete_role', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'assign_permission_to_role', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'remove_permission_from_role', 'guard_name' => 'sanctum']);

        // Permission Permissions
        Permission::create(['name' => 'view_permissions', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'create_permission', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'update_permission', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'delete_permission', 'guard_name' => 'sanctum']);

        // User Category Permissions
        Permission::create(['name' => 'view_user_categories', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'create_user_category', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'update_user_category', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'delete_user_category', 'guard_name' => 'sanctum']);

        // Badge Permissions
        Permission::create(['name' => 'view_badges', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'create_badge', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'update_badge', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'delete_badge', 'guard_name' => 'sanctum']);

        // Meal Menu Permissions
        Permission::create(['name' => 'view_meal_menus', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'create_meal_menu', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'update_meal_menu', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'delete_meal_menu', 'guard_name' => 'sanctum']);

        // Meal Schedule Permissions
        Permission::create(['name' => 'view_meal_schedules', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'create_meal_schedule', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'update_meal_schedule', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'delete_meal_schedule', 'guard_name' => 'sanctum']);

        // Category Discount Permissions
        Permission::create(['name' => 'view_category_discounts', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'create_category_discount', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'update_category_discount', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'delete_category_discount', 'guard_name' => 'sanctum']);

        // Meal Record Permissions
        Permission::create(['name' => 'view_meal_records', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'create_meal_record', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'update_meal_record', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'delete_meal_record', 'guard_name' => 'sanctum']);


        Permission::create(['name' => 'open_pos_device', 'guard_name' => 'sanctum']);
    }
}
