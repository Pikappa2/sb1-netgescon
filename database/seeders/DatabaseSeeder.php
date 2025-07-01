<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Chiama solo il seeder che abbiamo creato.
        $this->call([
            // SuperAdminSeeder::class, // Questo seeder è ora inglobato in TestSetupSeeder
            TestSetupSeeder::class, // Chiama il seeder principale di setup
        ]);
    }
}