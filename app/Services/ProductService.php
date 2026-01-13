<?php

namespace App\Services;

use App\DTOs\ProductFilterDTO;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Product Service
 * 
 * This service layer handles business logic for product operations.
 * It sits between the UI (Livewire) and the data layer (Repository),
 * providing a clean separation of concerns.
 */
class ProductService
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository
    ) {
    }

    /**
     * Get filtered products with pagination
     * 
     * This method demonstrates the use of DTOs for clean parameter passing
     * and repository pattern for data access abstraction.
     */
    public function getFilteredProducts(ProductFilterDTO $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->productRepository->getFilteredProducts($filters, $perPage);
    }

    /**
     * Get filter statistics (could be extended for analytics)
     */
    public function getFilterStats(ProductFilterDTO $filters): array
    {
        $products = $this->productRepository->getFilteredProducts($filters, PHP_INT_MAX);
        
        return [
            'total' => $products->total(),
            'has_filters' => $filters->hasFilters(),
        ];
    }
}
