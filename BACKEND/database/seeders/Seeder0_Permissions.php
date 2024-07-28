<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class Seeder0_Permissions extends Seeder
{
    public function run()
    {
        // Collaborateur Permissions
        Permission::create(['name' => 'voir_collaborateurs', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'importer_collaborateurs', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'creer_collaborateurs', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'modifier_collaborateurs', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'supprimer_collaborateurs', 'guard_name' => 'sanctum']);

        // Role Permissions
        Permission::create(['name' => 'voir_roles', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'creer_roles', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'modifier_roles', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'supprimer_roles', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'assigner_permission_a_roles', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'desassigner_permission_des_roles', 'guard_name' => 'sanctum']);

        // User Category Permissions
        Permission::create(['name' => 'voir_categorie_de_collaborateur', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'creer_categorie_de_collaborateur', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'modifier_categorie_de_collaborateur', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'supprimer_categorie_de_collaborateur', 'guard_name' => 'sanctum']);

        // Permissions des badges des collaborateurs
        Permission::create(['name' => 'voir_badges_collaborateurs', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'importer_badges_collaborateurs', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'gerer_badges_collaborateurs', 'guard_name' => 'sanctum']);

        // Permissions des badges des administrateurs
        Permission::create(['name' => 'voir_badges_administrateurs', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'importer_badges_administrateurs', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'gerer_badges_administrateurs', 'guard_name' => 'sanctum']);

        // Les composants des menus Permissions
        Permission::create(['name' => 'voir_composants_menus', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'creer_composants_menus', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'modifier_composants_menus', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'supprimer_composants_menus', 'guard_name' => 'sanctum']);

        // Les categories des menus Permissions
        Permission::create(['name' => 'voir_categories_menus', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'creer_categories_menus', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'modifier_categories_menus', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'supprimer_categories_menus', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'assigner_composants_menus', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'desassigner_composants_menus', 'guard_name' => 'sanctum']);

        // Les repas Permissions
        Permission::create(['name' => 'voir_repas', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'creer_repas', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'modifier_repas', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'supprimer_repas', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'assigner_categories_menus', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'desassigner_categories_menus', 'guard_name' => 'sanctum']);

        // Les profils des repas Permissions
        Permission::create(['name' => 'voir_profils_repas', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'creer_profils_repas', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'modifier_profils_repas', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'supprimer_profils_repas', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'assigner_repas', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'desassigner_repas', 'guard_name' => 'sanctum']);

        // Meal Record Permissions
        Permission::create(['name' => 'voir_enregistrements_repas', 'guard_name' => 'sanctum']);

        // gestion des POS (permission)
        Permission::create(['name' => 'voir_POS', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'creer_POS', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'modifier_POS', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'supprimer_POS', 'guard_name' => 'sanctum']);

        // Open POS device permission
        Permission::create(['name' => 'ouvrir_interface_de_pointage_sur_POS', 'guard_name' => 'sanctum']);

        // Gestion des subscribtions permissions
        Permission::create(['name' => 'gerer_subscribtions_des_admin', 'guard_name' => 'sanctum']);
    }
}
