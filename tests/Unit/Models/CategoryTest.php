<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_fillable_attributes(): void
    {
        $category = new Category();

        $this->assertEquals(['name', 'slug'], $category->getFillable());
    }

    /** @test */
    public function it_has_many_products_relationship(): void
    {
        $category = Category::factory()->create();

        // Create products for this category
        Product::factory()->count(2)->create(['category_id' => $category->id]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $category->products());
        $this->assertCount(2, $category->products);
    }

    /** @test */
    public function it_can_create_category_with_factory(): void
    {
        $category = Category::factory()->create([
            'name' => 'Test Category',
            'slug' => 'test-category'
        ]);

        $this->assertEquals('Test Category', $category->name);
        $this->assertEquals('test-category', $category->slug);
    }
}