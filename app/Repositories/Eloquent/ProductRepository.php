<?php

namespace App\Repositories\Eloquent;

use App\DTOs\ProductFilterDTO;
use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Eloquent implementation of ProductRepositoryInterface
 * 
 * This repository handles all database operations for products,
 * keeping the business logic clean and testable.
 */
class ProductRepository implements ProductRepositoryInterface
{
    public function __construct(
        private readonly Product $model
    ) {
    }

    /**
     * Get filtered and paginated products with eager loading
     */
    public function getFilteredProducts(ProductFilterDTO $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery()
            ->with(['brand', 'category']) // Eager load relationships
            ->search($filters->search)
            ->filterByCategories($filters->categoryIds)
            ->filterByBrands($filters->brandIds)
            ->orderBy($filters->sortBy, $filters->sortDirection);

        return $query->paginate($perPage);
    }

    /**
     * Get all products
     */
    public function all(): Collection
    {
        return $this->model->with(['brand', 'category'])->get();
    }

    /**
     * Find a product by ID
     */
    public function find(int $id): ?Product
    {
        return $this->model->with(['brand', 'category'])->find($id);
    }
}
