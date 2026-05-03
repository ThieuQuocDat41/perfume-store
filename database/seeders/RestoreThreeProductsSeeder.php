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
            'price' => 165,
            'image' => 'cn5.jpg',
            'stock' => 10,
        ]);

        Product::updateOrCreate([
            'name' => 'Eros Flame'
        ], [
            'brand' => 'Versace',
            'price' => 102,
            'image' => 'versace-eros.jpg',
            'stock' => 10,
        ]);

        Product::updateOrCreate([
            'name' => 'Sauvage Eau de Parfum'
        ], [
            'brand' => 'Dior',
            'price' => 145,
            'image' => 'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?q=80&w=800',
            'stock' => 10,
        ]);
    }
}
