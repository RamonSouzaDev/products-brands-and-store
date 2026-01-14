<?php

namespace Tests\Unit;

use App\DTOs\ProductFilterDTO;
use Tests\TestCase;

class ProductFilterDTOTest extends TestCase
{
    /** @test */
    public function it_can_be_created_with_default_values(): void
    {
        $dto = new ProductFilterDTO();

        $this->assertNull($dto->search);
        $this->assertEquals([], $dto->categoryIds);
        $this->assertEquals([], $dto->brandIds);
        $this->assertEquals('name', $dto->sortBy);
        $this->assertEquals('asc', $dto->sortDirection);
    }

    /** @test */
    public function it_can_be_created_with_custom_values(): void
    {
        $dto = new ProductFilterDTO(
            search: 'test search',
            categoryIds: [1, 2, 3],
            brandIds: [4, 5],
            sortBy: 'price',
            sortDirection: 'desc'
        );

        $this->assertEquals('test search', $dto->search);
        $this->assertEquals([1, 2, 3], $dto->categoryIds);
        $this->assertEquals([4, 5], $dto->brandIds);
        $this->assertEquals('price', $dto->sortBy);
        $this->assertEquals('desc', $dto->sortDirection);
    }

    /** @test */
    public function it_can_create_dto_from_array(): void
    {
        $data = [
            'search' => 'laptop',
            'categories' => ['1', '2', ''],
            'brands' => ['3', '', '4'],
            'sortBy' => 'price',
            'sortDirection' => 'desc'
        ];

        $dto = ProductFilterDTO::fromArray($data);

        $this->assertEquals('laptop', $dto->search);
        $this->assertEquals([1, 2], $dto->categoryIds); // filtered empty values
        $this->assertEquals([3, 4], $dto->brandIds); // filtered empty values
        $this->assertEquals('price', $dto->sortBy);
        $this->assertEquals('desc', $dto->sortDirection);
    }

    /** @test */
    public function it_can_create_dto_from_empty_array(): void
    {
        $dto = ProductFilterDTO::fromArray([]);

        $this->assertNull($dto->search);
        $this->assertEquals([], $dto->categoryIds);
        $this->assertEquals([], $dto->brandIds);
        $this->assertEquals('name', $dto->sortBy);
        $this->assertEquals('asc', $dto->sortDirection);
    }

    /** @test */
    public function it_can_convert_dto_to_array(): void
    {
        $dto = new ProductFilterDTO(
            search: 'phone',
            categoryIds: [1, 2],
            brandIds: [3],
            sortBy: 'price',
            sortDirection: 'desc'
        );

        $array = $dto->toArray();

        $expected = [
            'search' => 'phone',
            'categories' => [1, 2],
            'brands' => [3],
            'sortBy' => 'price',
            'sortDirection' => 'desc'
        ];

        $this->assertEquals($expected, $array);
    }

    /** @test */
    public function it_can_check_if_filters_are_active(): void
    {
        // No filters
        $dto = new ProductFilterDTO();
        $this->assertFalse($dto->hasFilters());

        // With search
        $dto = new ProductFilterDTO(search: 'test');
        $this->assertTrue($dto->hasFilters());

        // With categories
        $dto = new ProductFilterDTO(categoryIds: [1]);
        $this->assertTrue($dto->hasFilters());

        // With brands
        $dto = new ProductFilterDTO(brandIds: [2]);
        $this->assertTrue($dto->hasFilters());

        // With multiple filters
        $dto = new ProductFilterDTO(
            search: 'test',
            categoryIds: [1],
            brandIds: [2]
        );
        $this->assertTrue($dto->hasFilters());
    }

    /** @test */
    public function it_handles_empty_arrays_in_fromArray(): void
    {
        $data = [
            'categories' => [],
            'brands' => null,
        ];

        $dto = ProductFilterDTO::fromArray($data);

        $this->assertEquals([], $dto->categoryIds);
        $this->assertEquals([], $dto->brandIds);
    }
}