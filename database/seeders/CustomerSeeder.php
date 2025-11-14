<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use Illuminate\Support\Facades\Schema;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Schema::hasTable('customer_statuses') && Customer::query()->exists()) {
            return;
        }

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
