<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Définition des permissions par rôle
        
        $adminPermissions = [
            // Utilisateurs
            'voir_tous_utilisateurs',
            'creer_utilisateur',
            'modifier_utilisateur',
            'supprimer_utilisateur',
            'suspendre_utilisateur',
            
            // Entreprises
            'voir_toutes_entreprises',
            'valider_entreprise',
            'modifier_entreprise',
            'supprimer_entreprise',
            
            // Offres
            'voir_toutes_offres',
            'valider_offre',
            'modifier_offre',
            'supprimer_offre',
            
            // Administration
            'acces_admin_dashboard',
            'voir_statistiques',
            'gerer_roles_permissions',
            'gerer_parametres',
            'voir_logs_systeme',
        ];



        $demandeurPermissions = [
            // Offres
            'voir_offres',
            'voir_detail_offre',
            'sauvegarder_offre',
            'supprimer_offre_sauvegardee',
            
            // Candidatures
            'postuler_offre',
            'voir_mes_candidatures',
            'annuler_candidature',
            
            // Profil
            'voir_mon_profil',
            'modifier_mon_profil',
            'creer_mon_cv',
            'modifier_mon_cv',
            'ajouter_competence',
            'ajouter_experience',
            'ajouter_diplome',
            
            // Recommandations
            'voir_recommandations',
        ];

        // Combine all unique permissions
        $allPermissions = array_unique(array_merge($adminPermissions, $demandeurPermissions));

        // Create permissions
        foreach ($allPermissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }

        // 2. Création des rôles et assignation des permissions
        
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $roleAdmin->syncPermissions($adminPermissions);



        $roleDemandeur = Role::firstOrCreate(['name' => 'demandeur']);
        $roleDemandeur->syncPermissions($demandeurPermissions);
        
        // Optional: Create a default admin user
        $adminUser = \App\Models\User::firstOrCreate(
            ['email' => 'admin@acpe.test'],
            [
                'nom' => 'Admin',
                'prenom' => 'ACPE',
                'password' => bcrypt('password'),
            ]
        );
        if (!$adminUser->hasRole('admin')) {
            $adminUser->assignRole('admin');
        }
    }
}
