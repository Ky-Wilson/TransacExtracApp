<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Vérifier si le super admin n'existe pas déjà pour éviter les doublons
        $superAdmin = User::where('email', 'superadmin@example.com')->first();

        if (!$superAdmin) {
            User::create([
                'name' => 'Super Admin',
                'email' => 'superadmin2@example.com',
                'password' => Hash::make('password123'), // Mot de passe par défaut
                'role' => 'super_admin',
                'status' => true,
                'company_id' => null, // Pas d'entreprise associée pour le super admin
            ]);
        }
    }
}