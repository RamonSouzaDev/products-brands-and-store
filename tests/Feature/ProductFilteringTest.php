<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProductFilteringTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test data
        $brand1 = Brand::factory()->create(['name' => 'Apple', 'slug' => 'apple']);
        $brand2 = Brand::factory()->create(['name' => 'Samsung', 'slug' => 'samsung']);
        
        $category1 = Category::factory()->create(['name' => 'Smartphones', 'slug' => 'smartphones']);
        $category2 = Category::factory()->create(['name' => 'Laptops', 'slug' => 'laptops']);

        Product::factory()->create([
            'name' => 'iPhone 15',
            'brand_id' => $brand1->id,
            'category_id' => $category1->id,
        ]);

        Product::factory()->create([
            'name' => 'MacBook Pro',
            'brand_id' => $brand1->id,
            'category_id' => $category2->id,
        ]);

        Product::factory()->create([
            'name' => 'Galaxy S24',
            'brand_id' => $brand2->id,
            'category_id' => $category1->id,
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_search_products_by_name(): void
    {
        Livewire::test(\App\Livewire\ProductList::class)
            ->set('search', 'iPhone')
            ->assertSee('iPhone 15')
            ->assertDontSee('Galaxy S24');
    }

    /** @test */
    public function it_can_filter_by_single_category(): void
    {
        $category = Category::where('slug', 'smartphones')->first();

        Livewire::test(\App\Livewire\ProductList::class)
            ->set('selectedCategories', [$category->id])
            ->assertSee('iPhone 15')
            ->assertSee('Galaxy S24')
            ->assertDontSee('MacBook Pro');
    }

    /** @test */
    public function it_can_filter_by_multiple_categories(): void
    {
        $smartphones = Category::where('slug', 'smartphones')->first();
        $laptops = Category::where('slug', 'laptops')->first();

        Livewire::test(\App\Livewire\ProductList::class)
            ->set('selectedCategories', [$smartphones->id, $laptops->id])
            ->assertSee('iPhone 15')
            ->assertSee('MacBook Pro')
            ->assertSee('Galaxy S24');
    }

    /** @test */
    public function it_can_filter_by_single_brand(): void
    {
        $brand = Brand::where('slug', 'apple')->first();

        Livewire::test(\App\Livewire\ProductList::class)
            ->set('selectedBrands', [$brand->id])
            ->assertSee('iPhone 15')
            ->assertSee('MacBook Pro')
            ->assertDontSee('Galaxy S24');
    }

    /** @test */
    public function it_can_filter_by_multiple_brands(): void
    {
        $apple = Brand::where('slug', 'apple')->first();
        $samsung = Brand::where('slug', 'samsung')->first();

        Livewire::test(\App\Livewire\ProductList::class)
            ->set('selectedBrands', [$apple->id, $samsung->id])
            ->assertSee('iPhone 15')
            ->assertSee('MacBook Pro')
            ->assertSee('Galaxy S24');
    }

    /** @test */
    public function it_can_combine_search_with_filters(): void
    {
        $smartphones = Category::where('slug', 'smartphones')->first();

        Livewire::test(\App\Livewire\ProductList::class)
            ->set('search', 'iPhone')
            ->set('selectedCategories', [$smartphones->id])
            ->assertSee('iPhone 15')
            ->assertDontSee('MacBook Pro')
            ->assertDontSee('Galaxy S24');
    }

    /** @test */
    public function it_can_clear_all_filters(): void
    {
        $category = Category::where('slug', 'smartphones')->first();

        Livewire::test(\App\Livewire\ProductList::class)
            ->set('search', 'iPhone')
            ->set('selectedCategories', [$category->id])
            ->call('clearFilters')
            ->assertSet('search', '')
            ->assertSet('selectedCategories', [])
            ->assertSet('selectedBrands', []);
    }

    /** @test */
    public function it_persists_filters_in_url(): void
    {
        $category = Category::where('slug', 'smartphones')->first();
        $brand = Brand::where('slug', 'apple')->first();

        Livewire::test(\App\Livewire\ProductList::class)
            ->set('search', 'iPhone')
            ->set('selectedCategories', [$category->id])
            ->set('selectedBrands', [$brand->id])
            ->assertSet('search', 'iPhone')
            ->assertSet('selectedCategories', [$category->id])
            ->assertSet('selectedBrands', [$brand->id]);
    }

    /** @test */
    public function it_can_sort_products(): void
    {
        // Component starts with sortBy='name' and sortDirection='asc'
        // First call to sortBy('name') should toggle direction to 'desc'
        Livewire::test(\App\Livewire\ProductList::class)
            ->call('sortBy', 'name')
            ->assertSet('sortBy', 'name')
            ->assertSet('sortDirection', 'desc');

        // Test sorting by a different field
        Livewire::test(\App\Livewire\ProductList::class)
            ->call('sortBy', 'price')
            ->assertSet('sortBy', 'price')
            ->assertSet('sortDirection', 'asc');
    }
}
