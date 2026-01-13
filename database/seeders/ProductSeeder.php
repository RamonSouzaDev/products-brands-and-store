<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brandIds = Brand::pluck('id')->toArray();
        $categoryIds = Category::pluck('id')->toArray();

        // Create 100 products with random brands and categories
        Product::factory()
            ->count(100)
            ->create([
                'brand_id' => fn() => fake()->randomElement($brandIds),
                'category_id' => fn() => fake()->randomElement($categoryIds),
            ]);
    }
}
