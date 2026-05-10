<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class RestoreThreeProductsSeeder extends Seeder
{
    public function run()
    {
        Product::updateOrCreate([
            'name' => 'No. 5 Eau de Parfum'
        ], [
            'brand' => 'Chanel',
            'price_usd' => 165,
            'images' => json_encode(['cn5.jpg']),
            'stock' => 10,
        ]);

        Product::updateOrCreate([
            'name' => 'Eros Flame'
        ], [
            'brand' => 'Versace',
            'price_usd' => 102,
            'images' => json_encode(['versace-eros.jpg']),
            'stock' => 10,
        ]);

        Product::updateOrCreate([
            'name' => 'Sauvage Eau de Parfum'
        ], [
            'brand' => 'Dior',
            'price_usd' => 145,
            'images' => json_encode(['https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?q=80&w=800']),
            'stock' => 10,
        ]);
    }
}
