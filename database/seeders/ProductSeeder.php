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
        $samples = [
            ['name'=>'No. 5 Eau de Parfum','brand'=>'Chanel','price'=>165,'gender'=>'female','top'=>'Bergamot, Aldehydes','heart'=>'Rose, Jasmine','base'=>'Sandalwood, Vanilla'],
            ['name'=>'Sauvage Eau de Parfum','brand'=>'Dior','price'=>145,'gender'=>'male','top'=>'Bergamot, Pepper','heart'=>'Lavender, Sichuan Pepper','base'=>'Ambroxan, Vanilla'],
            ['name'=>'Bleu de Chanel','brand'=>'Chanel','price'=>150,'gender'=>'male','top'=>'Grapefruit, Lemon','heart'=>'Ginger, Nutmeg','base'=>'Cedar, Sandalwood'],
            ['name'=>'Oud Wood','brand'=>'Tom Ford','price'=>295,'gender'=>'unisex','top'=>'Rosewood, Cardamom','heart'=>'Oud, Sandalwood','base'=>'Vanilla, Amber'],
            ['name'=>'Aventus','brand'=>'Creed','price'=>495,'gender'=>'male','top'=>'Pineapple, Bergamot','heart'=>'Rose, Jasmine','base'=>'Oakmoss, Ambergris'],
            ['name'=>'Baccarat Rouge 540','brand'=>'MFK','price'=>325,'gender'=>'unisex','top'=>'Saffron, Jasmine','heart'=>'Amberwood, Ambergris','base'=>'Fir Resin, Cedar'],
        ];

        $imagesPool = [
            'https://images.unsplash.com/photo-1541643600914-78b084683601?q=80&w=1200',
            'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?q=80&w=1200',
            'https://images.unsplash.com/photo-1523293182086-7651a899d37f?q=80&w=1200',
            'https://images.unsplash.com/photo-1557170334-a9632e77c6e4?q=80&w=1200',
        ];

        // create 40 products
        for ($i=0;$i<40;$i++) {
            $base = $samples[$i % count($samples)];
            $name = $base['name'] . ' ' . ($i+1);
            $brand = $base['brand'];
            $price = $base['price'] + ($i % 5) * 5;
            $gender = $base['gender'];

            $imgs = [
                $imagesPool[$i % count($imagesPool)],
                $imagesPool[($i+1) % count($imagesPool)],
                $imagesPool[($i+2) % count($imagesPool)],
            ];

            \App\Models\Product::create([
                'name' => $name,
                'brand' => $brand,
                'price_usd' => $price,
                'stock' => 50,
                'short_description' => 'A refined scent with roots in classic European perfumery. Perfect for special occasions and daily elegance.',
                'top_notes' => $base['top'],
                'heart_notes' => $base['heart'],
                'base_notes' => $base['base'],
                'gender' => $gender,
                'images' => json_encode($imgs),
                'tags' => json_encode(['Luxury']),
            ]);
        }
    }
}
