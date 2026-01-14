<?php

namespace Tests\Unit\Services;

use App\DTOs\ProductFilterDTO;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Services\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    use RefreshDatabase;

    private ProductService $productService;
    private ProductRepositoryInterface $mockRepository;

    protected function setUp(): void
    {
        parent::setUp();

        // Create mock repository
        $this->mockRepository = Mockery::mock(ProductRepositoryInterface::class);

        // Create service with mock repository
        $this->productService = new ProductService($this->mockRepository);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_can_get_filtered_products(): void
    {
        $filters = new ProductFilterDTO(
            search: 'laptop',
            categoryIds: [1],
            brandIds: [2],
            sortBy: 'price',
            sortDirection: 'desc'
        );

        // Create a mock paginator
        $mockPaginator = Mockery::mock(\Illuminate\Contracts\Pagination\LengthAwarePaginator::class);
        $mockPaginator->shouldReceive('total')->andReturn(1);

        $this->mockRepository
            ->shouldReceive('getFilteredProducts')
            ->once()
            ->with($filters, 15)
            ->andReturn($mockPaginator);

        $result = $this->productService->getFilteredProducts($filters);

        $this->assertEquals($mockPaginator, $result);
    }

    /** @test */
    public function it_can_get_filtered_products_with_custom_per_page(): void
    {
        $filters = new ProductFilterDTO();
        // Create a mock paginator
        $mockPaginator = Mockery::mock(\Illuminate\Contracts\Pagination\LengthAwarePaginator::class);
        $mockPaginator->shouldReceive('total')->andReturn(0);

        $this->mockRepository
            ->shouldReceive('getFilteredProducts')
            ->once()
            ->with($filters, 10)
            ->andReturn($mockPaginator);

        $result = $this->productService->getFilteredProducts($filters, 10);

        $this->assertEquals($mockPaginator, $result);
    }

    /** @test */
    public function it_can_get_filter_stats(): void
    {
        $filters = new ProductFilterDTO(search: 'test');

        $mockPaginator = Mockery::mock(\Illuminate\Contracts\Pagination\LengthAwarePaginator::class);
        $mockPaginator->shouldReceive('total')->andReturn(25);

        $this->mockRepository
            ->shouldReceive('getFilteredProducts')
            ->once()
            ->with($filters, Mockery::any())
            ->andReturn($mockPaginator);

        $stats = $this->productService->getFilterStats($filters);

        $this->assertEquals([
            'total' => 25,
            'has_filters' => true
        ], $stats);
    }

    /** @test */
    public function it_returns_correct_stats_when_no_filters(): void
    {
        $filters = new ProductFilterDTO(); // No filters

        $mockPaginator = Mockery::mock(\Illuminate\Contracts\Pagination\LengthAwarePaginator::class);
        $mockPaginator->shouldReceive('total')->andReturn(100);

        $this->mockRepository
            ->shouldReceive('getFilteredProducts')
            ->once()
            ->andReturn($mockPaginator);

        $stats = $this->productService->getFilterStats($filters);

        $this->assertEquals([
            'total' => 100,
            'has_filters' => false
        ], $stats);
    }
}