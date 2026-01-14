<?php

namespace Tests\Unit\Models;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_fillable_attributes(): void
    {
        $brand = new Brand();

        $this->assertEquals(['name', 'slug'], $brand->getFillable());
    }

    /** @test */
    public function it_has_many_products_relationship(): void
    {
        $brand = Brand::factory()->create();

        // Create products for this brand
        Product::factory()->count(3)->create(['brand_id' => $brand->id]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $brand->products());
        $this->assertCount(3, $brand->products);
    }

    /** @test */
    public function it_can_create_brand_with_factory(): void
    {
        $brand = Brand::factory()->create([
            'name' => 'Test Brand',
            'slug' => 'test-brand'
        ]);

        $this->assertEquals('Test Brand', $brand->name);
        $this->assertEquals('test-brand', $brand->slug);
    }
}