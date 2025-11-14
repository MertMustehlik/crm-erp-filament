<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Unit;
use Illuminate\Support\Facades\Schema;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Schema::hasTable('units') && Unit::query()->exists()) {
            return;
        }


        $data = [
            ['name' => 'Adet'],
            ['name' => 'Metre'],
            ['name' => 'Metrekare'],
            ['name' => 'Kilogram'],
            ['name' => 'Litre'],
        ];

        foreach ($data as $unit) {
            Unit::firstOrCreate([
                'name' => $unit['name'],
            ]);
        }
    }
}
