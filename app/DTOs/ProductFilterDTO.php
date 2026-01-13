<?php

namespace App\DTOs;

use Illuminate\Support\Arr;

/**
 * Data Transfer Object for Product Filtering
 * 
 * This DTO encapsulates all filter parameters for product search,
 * providing a clean interface between the UI layer and business logic.
 */
class ProductFilterDTO
{
    public function __construct(
        public readonly ?string $search = null,
        public readonly array $categoryIds = [],
        public readonly array $brandIds = [],
        public readonly string $sortBy = 'name',
        public readonly string $sortDirection = 'asc',
    ) {
    }

    /**
     * Create DTO from array (e.g., from request input)
     * Uses Laravel 12's Arr::onlyValues() for clean filtering
     */
    public static function fromArray(array $data): self
    {
        return new self(
            search: $data['search'] ?? null,
            categoryIds: array_filter((array) ($data['categories'] ?? [])),
            brandIds: array_filter((array) ($data['brands'] ?? [])),
            sortBy: $data['sortBy'] ?? 'name',
            sortDirection: $data['sortDirection'] ?? 'asc',
        );
    }

    /**
     * Convert DTO to array
     */
    public function toArray(): array
    {
        return [
            'search' => $this->search,
            'categories' => $this->categoryIds,
            'brands' => $this->brandIds,
            'sortBy' => $this->sortBy,
            'sortDirection' => $this->sortDirection,
        ];
    }

    /**
     * Check if any filters are active
     */
    public function hasFilters(): bool
    {
        return !empty($this->search) 
            || !empty($this->categoryIds) 
            || !empty($this->brandIds);
    }
}
