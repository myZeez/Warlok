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
        User::factory()->create([
            'name' => 'Admin Warlok',
            'email' => 'admin@warlok.test',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->call([
            WilayahSeeder::class,
            RegionSeeder::class,
            CategorySeeder::class,
            UmkmProductSeeder::class,
        ]);
    }
}
