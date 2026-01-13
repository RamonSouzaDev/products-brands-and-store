<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
    <div class="container mx-auto px-4 py-8">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">Product Catalog</h1>
            <p class="text-gray-600 dark:text-gray-400">Discover our amazing collection of products</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            {{-- Filters Sidebar --}}
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 sticky top-4">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Filters</h2>
                        @if($hasFilters)
                            <button wire:click="clearFilters" 
                                    class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">
                                Clear All
                            </button>
                        @endif
                    </div>

                    {{-- Search --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Search Products
                        </label>
                        <input type="text" 
                               wire:model.live.debounce.300ms="search" 
                               placeholder="Search by name..."
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white transition-all">
                    </div>

                    {{-- Categories --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                            Categories
                        </label>
                        <div class="space-y-2 max-h-64 overflow-y-auto">
                            @foreach($categories as $category)
                                <label class="flex items-center p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                                    <input type="checkbox" 
                                           wire:model.live="selectedCategories" 
                                           value="{{ $category->id }}"
                                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700">
                                    <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Brands --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                            Brands
                        </label>
                        <div class="space-y-2 max-h-64 overflow-y-auto">
                            @foreach($brands as $brand)
                                <label class="flex items-center p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                                    <input type="checkbox" 
                                           wire:model.live="selectedBrands" 
                                           value="{{ $brand->id }}"
                                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700">
                                    <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">{{ $brand->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Products Grid --}}
            <div class="lg:col-span-3">
                {{-- Results Header --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 mb-6 flex items-center justify-between">
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        Showing {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products
                    </div>
                    <div class="flex gap-2">
                        <button wire:click="sortBy('name')" 
                                class="px-3 py-1 text-sm rounded-lg {{ $sortBy === 'name' ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }} transition-colors">
                            Name {{ $sortBy === 'name' ? ($sortDirection === 'asc' ? '↑' : '↓') : '' }}
                        </button>
                        <button wire:click="sortBy('price')" 
                                class="px-3 py-1 text-sm rounded-lg {{ $sortBy === 'price' ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }} transition-colors">
                            Price {{ $sortBy === 'price' ? ($sortDirection === 'asc' ? '↑' : '↓') : '' }}
                        </button>
                    </div>
                </div>

                {{-- Products --}}
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-6">
                    @forelse($products as $product)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                            <div class="p-6">
                                <div class="flex items-start justify-between mb-3">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white line-clamp-2">
                                        {{ $product->name }}
                                    </h3>
                                </div>
                                
                                <div class="flex gap-2 mb-3">
                                    <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs rounded-full">
                                        {{ $product->category->name }}
                                    </span>
                                    <span class="px-2 py-1 bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 text-xs rounded-full">
                                        {{ $product->brand->name }}
                                    </span>
                                </div>

                                <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-3">
                                    {{ $product->description }}
                                </p>

                                <div class="flex items-center justify-between">
                                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                        ${{ number_format($product->price, 2) }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        Stock: {{ $product->stock }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <div class="text-gray-400 dark:text-gray-500 text-lg">
                                No products found matching your filters.
                            </div>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
