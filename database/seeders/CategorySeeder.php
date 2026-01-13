<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Electronics',
            'Smartphones',
            'Laptops',
            'Tablets',
            'Cameras',
            'Audio',
            'Wearables',
            'Gaming',
            'Sports',
            'Clothing',
            'Footwear',
            'Accessories',
            'Home & Garden',
            'Books',
            'Toys',
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category,
                'slug' => \Illuminate\Support\Str::slug($category),
            ]);
        }
    }
}
