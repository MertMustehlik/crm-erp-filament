<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\CustomerStatusSeeder;
use Database\Seeders\UnitSeeder;
use Database\Seeders\ProductSeeder;

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
            RolePermissionSeeder::class,
            CustomerStatusSeeder::class,
            ProductSeeder::class,
        ]);

        Customer::create([
            'type' => 'individual',
            'first_name' => 'Customer',
            'last_name' => '1',
            'email' => 'customer1@example.com',
            'phone' => '+905536985598',
            'address' => '123 Main St',
            'status_id' => 1,
        ]);

        Customer::create([
            'type' => 'individual',
            'first_name' => 'Customer',
            'last_name' => '2',
            'email' => 'customer2@example.com',
            'phone' => '+905387695890',
            'address' => '456 Elm St',
            'status_id' => 2,
        ]);
    }
}
