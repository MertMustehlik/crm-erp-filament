<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Database\Seeders\UnitSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\CustomerStatusSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UnitSeeder::class,
            UserSeeder::class,
            CustomerSeeder::class,
            RolePermissionSeeder::class,
            CustomerStatusSeeder::class,
            ProductSeeder::class,
        ]);
    }
}
