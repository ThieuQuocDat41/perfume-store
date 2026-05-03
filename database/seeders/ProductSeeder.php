<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    $products = [
        [ 'name' => "No. 5 Eau de Parfum", 'brand'=>'Chanel', 'price'=>165, 'image'=>'https://images.unsplash.com/photo-1541643600914-78b084683601?q=80&w=800' ],
        [ 'name' => "Sauvage Eau de Parfum", 'brand'=>'Dior', 'price'=>145, 'image'=>'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?q=80&w=800' ],
        [ 'name' => "Bleu de Chanel", 'brand'=>'Chanel', 'price'=>150, 'image'=>'https://images.unsplash.com/photo-1523293182086-7651a899d37f?q=80&w=800' ],
        [ 'name' => "Eros Flame", 'brand'=>'Versace', 'price'=>102, 'image'=>'https://images.unsplash.com/photo-1557170334-a9632e77c6e4?q=80&w=800' ],
        [ 'name' => "Black Opium", 'brand'=>'YSL', 'price'=>155, 'image'=>'https://images.unsplash.com/photo-1615484477778-ca3b77940c25?q=80&w=800' ],
        [ 'name' => "Oud Wood", 'brand'=>'Tom Ford', 'price'=>295, 'image'=>'https://images.unsplash.com/photo-1583445013765-48c2201c8062?q=80&w=800' ],
        [ 'name' => "Tobacco Vanille", 'brand'=>'Tom Ford', 'price'=>295, 'image'=>'https://images.unsplash.com/photo-1594035910387-fea47794261f?q=80&w=800' ],
        [ 'name' => "J'adore", 'brand'=>'Dior', 'price'=>150, 'image'=>'https://images.unsplash.com/photo-1590736704728-f4730bb30770?q=80&w=800' ],
        [ 'name' => "Gucci Bloom", 'brand'=>'Gucci', 'price'=>135, 'image'=>'https://images.unsplash.com/photo-1605615840892-26184d4bcdba?q=80&w=800' ],
        [ 'name' => "La Nuit de l'Homme", 'brand'=>'YSL', 'price'=>110, 'image'=>'https://images.unsplash.com/photo-1616190819543-e283944220ec?q=80&w=800' ],
        [ 'name' => "Acqua di Gio", 'brand'=>'Giorgio Armani', 'price'=>115, 'image'=>'https://images.unsplash.com/photo-1594035910387-fea47794261f?q=80&w=800' ],
        [ 'name' => "Aventus", 'brand'=>'Creed', 'price'=>495, 'image'=>'https://images.unsplash.com/photo-1613522144666-8d659a192f64?q=80&w=800' ],
        [ 'name' => "Baccarat Rouge 540", 'brand'=>'MFK', 'price'=>325, 'image'=>'https://images.unsplash.com/photo-1619994403073-2cec844b8e63?q=80&w=800' ],
        [ 'name' => "Flowerbomb", 'brand'=>'Viktor&Rolf', 'price'=>142, 'image'=>'https://images.unsplash.com/photo-1595425970377-c9703cf48b6d?q=80&w=800' ],
        [ 'name' => "Light Blue", 'brand'=>'Dolce & Gabbana', 'price'=>110, 'image'=>'https://images.unsplash.com/photo-1512568400610-62da28bc8a13?q=80&w=800' ],
        [ 'name' => "Le Male", 'brand'=>'Jean Paul Gaultier', 'price'=>120, 'image'=>'https://images.unsplash.com/photo-1615484477201-9f4953340fab?q=80&w=800' ],
        [ 'name' => "My Way", 'brand'=>'Giorgio Armani', 'price'=>150, 'image'=>'https://images.unsplash.com/photo-1615484476889-4679261bb93d?q=80&w=800' ],
        [ 'name' => "Mon Paris", 'brand'=>'YSL', 'price'=>130, 'image'=>'https://images.unsplash.com/photo-1594035910387-fea47794261f?q=80&w=800' ],
        [ 'name' => "Terre d'Hermès", 'brand'=>'Hermès', 'price'=>140, 'image'=>'https://images.unsplash.com/photo-1523293182086-7651a899d37f?q=80&w=800' ],
        [ 'name' => "Jazz Club", 'brand'=>'Maison Margiela', 'price'=>160, 'image'=>'https://images.unsplash.com/photo-1543451906-08cd2e38c715?q=80&w=800' ],
    ];

    foreach ($products as $p) {
        \App\Models\Product::create([
            'name' => $p['name'],
            'brand' => $p['brand'],
            'price' => $p['price'],
            'stock' => 50,
            'description' => '',
            'image' => $p['image'],
            'tags' => json_encode([]),
        ]);
    }
    }
}
