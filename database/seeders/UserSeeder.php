<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        if (Schema::hasTable('users') && User::query()->doesntExist()) {
            User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@demo.com',
                'password' => '123123',
            ]);
        }
    }
}
