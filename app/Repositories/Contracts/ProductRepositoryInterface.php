<?php

namespace App\Repositories\Contracts;

use App\DTOs\ProductFilterDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Product Repository Interface
 * 
 * Defines the contract for product data access operations.
 * This abstraction allows for easy testing and potential implementation swapping.
 */
interface ProductRepositoryInterface
{
    /**
     * Get filtered and paginated products
     */
    public function getFilteredProducts(ProductFilterDTO $filters, int $perPage = 15): LengthAwarePaginator;

    /**
     * Get all products (for testing or admin purposes)
     */
    public function all(): Collection;

    /**
     * Find a product by ID
     */
    public function find(int $id): ?\App\Models\Product;
}
