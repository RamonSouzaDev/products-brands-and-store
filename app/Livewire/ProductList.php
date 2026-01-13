<?php

namespace App\Livewire;

use App\DTOs\ProductFilterDTO;
use App\Models\Brand;
use App\Models\Category;
use App\Services\ProductService;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ProductList extends Component
{
    use WithPagination;

    // URL-persisted properties for filter persistence on page refresh
    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'cat')]
    public array $selectedCategories = [];

    #[Url(as: 'brand')]
    public array $selectedBrands = [];

    #[Url]
    public string $sortBy = 'name';

    #[Url]
    public string $sortDirection = 'asc';

    public int $perPage = 15;

    /**
     * Reset pagination when filters change
     */
    public function updated($property): void
    {
        if (in_array($property, ['search', 'selectedCategories', 'selectedBrands', 'sortBy', 'sortDirection'])) {
            $this->resetPage();
        }
    }

    /**
     * Clear all filters
     */
    public function clearFilters(): void
    {
        $this->reset(['search', 'selectedCategories', 'selectedBrands']);
        $this->resetPage();
    }

    /**
     * Toggle sort direction
     */
    public function sortBy(string $field): void
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    /**
     * Render the component
     */
    public function render(ProductService $productService)
    {
        // Create DTO from current filter state
        $filters = new ProductFilterDTO(
            search: $this->search ?: null,
            categoryIds: $this->selectedCategories,
            brandIds: $this->selectedBrands,
            sortBy: $this->sortBy,
            sortDirection: $this->sortDirection,
        );

        // Get filtered products through service layer
        $products = $productService->getFilteredProducts($filters, $this->perPage);

        // Get all brands and categories for filter UI
        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('livewire.product-list', [
            'products' => $products,
            'brands' => $brands,
            'categories' => $categories,
            'hasFilters' => $filters->hasFilters(),
        ]);
    }
}
