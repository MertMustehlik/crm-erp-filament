<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Schema::hasTable('products') && Product::query()->doesntExist()) {
            $products = [
                [
                    'sku' => 'MAT-001',
                    'name' => 'Boru Çelik 6 inch',
                    'price' => 1200.50,
                    'vat_percent' => 20,
                    'unit_id' => 1,
                ],
                [
                    'sku' => 'CHEM-001',
                    'name' => 'Bentonit Çamuru (Drilling Mud)',
                    'price' => 250.75,
                    'vat_percent' => 20,
                    'unit_id' => 2,
                ],
                [
                    'sku' => 'EQP-001',
                    'name' => 'Pompa Motoru 500HP',
                    'price' => 15000.00,
                    'vat_percent' => 20,
                    'unit_id' => 3,
                ],
                [
                    'sku' => 'TOOL-001',
                    'name' => 'Basınç Ölçer Manometre',
                    'price' => 350.00,
                    'vat_percent' => 20,
                    'unit_id' => 3,
                ],
                [
                    'sku' => 'SP-001',
                    'name' => 'Vanalar (Çeşitli Boyut)',
                    'price' => 500.00,
                    'vat_percent' => 20,
                    'unit_id' => 3,
                ],
                [
                    'sku' => 'MAT-002',
                    'name' => 'Hidrokarbon Tankı 1000L',
                    'price' => 2000.00,
                    'vat_percent' => 20,
                    'unit_id' => 1,
                ],
            ];

            foreach ($products as $product) {
                Product::create([
                    'sku' => $product['sku'],
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'vat_percent' => $product['vat_percent'],
                    'unit_id' => $product['unit_id'],
                ]);
            }
        }
    }
}
