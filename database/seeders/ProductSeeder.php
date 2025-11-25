<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shoes = [
            // Men's Shoes
            [
                'name' => 'Nike Air Max 270 - Black/White',
                'gender' => 'MALE',
                'price' => 1850000,
                'description' => 'Classic Nike Air Max 270 with comfortable cushioning and modern design. Perfect for casual wear and light activities.',
                'photo' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80'
            ],
            [
                'name' => 'Adidas Ultraboost 22 - Core Black',
                'gender' => 'MALE',
                'price' => 2450000,
                'description' => 'Premium running shoes with Boost technology for maximum energy return and comfort during long runs.',
                'photo' => 'https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80'
            ],
            [
                'name' => 'New Balance 574 - Navy Blue',
                'gender' => 'MALE',
                'price' => 1250000,
                'description' => 'Iconic New Balance 574 series with ENCAP midsole technology for superior comfort and durability.',
                'photo' => 'https://images.unsplash.com/photo-1549289524-06cf8837ace5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80'
            ],
            [
                'name' => 'Puma RS-X³ - Triple Black',
                'gender' => 'MALE',
                'price' => 1650000,
                'description' => 'Bold and chunky sneaker design with RS (Running System) technology for enhanced comfort.',
                'photo' => 'https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80'
            ],
            [
                'name' => 'Vans Old Skool - Black/White',
                'gender' => 'MALE',
                'price' => 750000,
                'description' => 'Classic skate shoes with durable canvas upper and iconic side stripe. Perfect for casual wear.',
                'photo' => 'https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80'
            ],
            [
                'name' => 'Converse Chuck Taylor All Star - White',
                'gender' => 'MALE',
                'price' => 650000,
                'description' => 'Timeless canvas sneakers that never go out of style. Suitable for various casual occasions.',
                'photo' => 'https://images.unsplash.com/photo-1449505278894-297fdb3edbc1?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80'
            ],
            [
                'name' => 'Reebok Classic Leather - White',
                'gender' => 'MALE',
                'price' => 950000,
                'description' => 'Vintage-inspired leather sneakers with soft cushioning and timeless design.',
                'photo' => 'https://images.unsplash.com/photo-1551107696-a4b0c5a0d9a2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80'
            ],
            [
                'name' => 'Skechers Go Walk 5 - Charcoal',
                'gender' => 'MALE',
                'price' => 850000,
                'description' => 'Lightweight walking shoes with 5GEN cushioning and flexible sole for all-day comfort.',
                'photo' => 'https://images.unsplash.com/photo-1560769624-6b00693a1c05?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80'
            ],

            // Women's Shoes
            [
                'name' => 'Nike Air Force 1 - Triple White',
                'gender' => 'FEMALE',
                'price' => 1650000,
                'description' => 'Iconic basketball-inspired sneakers with leather upper and Air-Sole unit for comfort.',
                'photo' => 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80'
            ],
            [
                'name' => 'Adidas NMD_R1 - Pink Glow',
                'gender' => 'FEMALE',
                'price' => 1950000,
                'description' => 'Modern lifestyle sneakers with Boost midsole and stylish primeknit upper.',
                'photo' => 'https://images.unsplash.com/photo-1575537302964-96cd47c06b1b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80'
            ],
            [
                'name' => 'New Balance 327 - White/Navy',
                'gender' => 'FEMALE',
                'price' => 1350000,
                'description' => 'Retro-inspired running shoes with oversized N logo and comfortable EVA midsole.',
                'photo' => 'https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80'
            ],
            [
                'name' => 'Puma Cali Dream - White/Pink',
                'gender' => 'FEMALE',
                'price' => 1450000,
                'description' => 'Fashion-forward sneakers with leather and suede upper, perfect for street style.',
                'photo' => 'https://images.unsplash.com/photo-1543163521-1bf539c55dd2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80'
            ],
            [
                'name' => 'Vans Slip-On - Checkerboard',
                'gender' => 'FEMALE',
                'price' => 680000,
                'description' => 'Easy-to-wear slip-on shoes with iconic checkerboard pattern and padded collar.',
                'photo' => 'https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80'
            ],
            [
                'name' => 'Converse Chuck 70 - Pastel Pink',
                'gender' => 'FEMALE',
                'price' => 850000,
                'description' => 'Premium version of the classic Chuck Taylor with better materials and cushioning.',
                'photo' => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80'
            ],
            [
                'name' => 'Reebok Club C 85 - Vintage White',
                'gender' => 'FEMALE',
                'price' => 1100000,
                'description' => 'Tennis-inspired vintage sneakers with full-grain leather upper and soft cushioning.',
                'photo' => 'https://images.unsplash.com/photo-1551107696-a4b0c5a0d9a2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80'
            ],
            [
                'name' => 'Skechers D Lux Walkers - Black',
                'gender' => 'FEMALE',
                'price' => 780000,
                'description' => 'Comfortable walking shoes with memory foam insole and flexible outsole.',
                'photo' => 'https://images.unsplash.com/photo-1560769624-6b00693a1c05?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80'
            ],

            // Unisex Shoes
            [
                'name' => 'Nike Air Jordan 1 Mid - Light Smoke Grey',
                'gender' => 'UNISEX',
                'price' => 2250000,
                'description' => 'Iconic basketball sneakers with Air-Sole unit and classic high-top design.',
                'photo' => 'https://images.unsplash.com/photo-1600269452121-4f2416e55c28?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80'
            ],
            [
                'name' => 'Adidas Stan Smith - Green/White',
                'gender' => 'UNISEX',
                'price' => 1250000,
                'description' => 'Classic tennis shoes with leather upper and minimalist design. Timeless style.',
                'photo' => 'https://images.unsplash.com/photo-1543508282-6319a3e2621f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80'
            ],
            [
                'name' => 'New Balance 990v5 - Grey',
                'gender' => 'UNISEX',
                'price' => 2850000,
                'description' => 'Premium running shoes with ENCAP midsole technology and pigskin/mesh upper.',
                'photo' => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80'
            ],
            [
                'name' => 'On Cloud 5 - Black/White',
                'gender' => 'UNISEX',
                'price' => 1950000,
                'description' => 'Lightweight running shoes with CloudTec® technology for soft landings and explosive take-offs.',
                'photo' => 'https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80'
            ]
        ];

        foreach ($shoes as $shoe) {
            $code = 'SHOE' . str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT) . Str::upper(Str::random(3));

            Product::create([
                'code' => $code,
                'name' => $shoe['name'],
                'price' => $shoe['price'],
                'description' => $shoe['description'],
                'stock' => mt_rand(5, 50), // Random stock between 5-50
                'gender' => $shoe['gender'],
                'photo' => $shoe['photo'],
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('20 shoe products with images created successfully!');
    }
}
