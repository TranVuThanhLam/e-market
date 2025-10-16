<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $electronicsId = Category::where('slug', 'electronics')->first()->id;
        $fashionId = Category::where('slug', 'fashion')->first()->id;
        $homeGardenId = Category::where('slug', 'home-garden')->first()->id;

        $products = [
            [
                'category_id' => $electronicsId,
                'name' => 'Laptop Dell XPS 15',
                'slug' => 'laptop-dell-xps-15',
                'description' => 'High-performance laptop with Intel Core i7',
                'price' => 1299.99,
                'sale_price' => 1199.99,
                'sku' => 'DELL-XPS-15',
                'stock' => 50,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'category_id' => $electronicsId,
                'name' => 'iPhone 15 Pro',
                'slug' => 'iphone-15-pro',
                'description' => 'Latest iPhone with A17 Pro chip',
                'price' => 999.99,
                'sale_price' => 949.99,
                'sku' => 'IPHONE-15-PRO',
                'stock' => 100,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'category_id' => $electronicsId,
                'name' => 'Sony WH-1000XM5 Headphones',
                'slug' => 'sony-wh-1000xm5',
                'description' => 'Noise cancelling wireless headphones',
                'price' => 399.99,
                'sku' => 'SONY-WH-1000XM5',
                'stock' => 75,
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'category_id' => $fashionId,
                'name' => 'Nike Air Max Sneakers',
                'slug' => 'nike-air-max',
                'description' => 'Comfortable and stylish sneakers',
                'price' => 129.99,
                'sale_price' => 99.99,
                'sku' => 'NIKE-AIR-MAX',
                'stock' => 200,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'category_id' => $fashionId,
                'name' => 'Levi\'s Denim Jacket',
                'slug' => 'levis-denim-jacket',
                'description' => 'Classic denim jacket',
                'price' => 89.99,
                'sku' => 'LEVIS-DENIM',
                'stock' => 150,
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'category_id' => $homeGardenId,
                'name' => 'Robot Vacuum Cleaner',
                'slug' => 'robot-vacuum',
                'description' => 'Smart robot vacuum with auto-charging',
                'price' => 299.99,
                'sale_price' => 249.99,
                'sku' => 'ROBOT-VAC-001',
                'stock' => 60,
                'is_featured' => true,
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
