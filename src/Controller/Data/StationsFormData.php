<?php

declare(strict_types=1);

namespace App\Controller\Data;

class StationsFormData
{
    private ?string $filterByRoad;
    private ?string $filterByStationName;
    private ?string $filterByPosition;
    private ?string $filterByPavilion;
    private string $searchQuery;
    private string $sortByField;
    private string $isSortAsc;
    private string $pageNumber;

    /**
     * @param string|null $filterByRoad
     * @param string|null $filterByStationName
     * @param string|null $filterByPosition
     * @param string|null $filterByPavilion
     * @param string $searchQuery
     * @param string $sortByField
     * @param string $isSortAsc
     * @param string $pageNumber
     */
    public function __construct(
        ?string $filterByRoad,
        ?string $filterByStationName,
        ?string $filterByPosition,
        ?string $filterByPavilion,
        string $searchQuery,
        string $sortByField,
        string $isSortAsc,
        string $pageNumber
    ) {
        $this->filterByRoad = $filterByRoad;
        $this->filterByStationName = $filterByStationName;
        $this->filterByPosition = $filterByPosition;
        $this->filterByPavilion = $filterByPavilion;
        $this->searchQuery = $searchQuery;
        $this->sortByField = $sortByField;
        $this->isSortAsc = $isSortAsc;
        $this->pageNumber = $pageNumber;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'filter_by_road' => $this->filterByRoad,
            'filter_by_station_name' => $this->filterByStationName,
            'filter_by_position' => $this->filterByPosition,
            'filter_by_pavilion' => $this->filterByPavilion,
            'search_query' => $this->searchQuery,
            'sort_by' => $this->sortByField,
            'is_sort_asc' => $this->isSortAsc === 'true' ? 1 : 0,
            'page_number' => $this->pageNumber
        ];
    }

    /**
     * @param array $parameters
     * @return static
     */
    public static function fromArray(array $parameters): self
    {
        return new self(
            $parameters['filter_by_road'] ?: null,
            $parameters['filter_by_station_name'] ?: null,
            $parameters['filter_by_position'] ?: null,
            $parameters['filter_by_pavilion'] ?: null,
            $parameters['search_query'] ?? '',
            $parameters['sort_by'] ?? 'road',
            $parameters['is_sort_asc'] ?? 'true',
            $parameters['page_number'] ?? '1'
        );
    }
    public function getFilterByRoad(): ?string
    {
        return $this->filterByRoad;
    }
    public function getFilterByStationName(): ?string
    {
        return $this->filterByStationName;
    }
    public function getFilterByPosition(): ?string
    {
        return $this->filterByPosition;
    }
    public function getFilterByPavilion(): ?string
    {
        return $this->filterByPavilion;
    }
    public function getSearchQuery(): string
    {
        return $this->searchQuery;
    }
    public function getOrderByField(): string
    {
        return $this->sortByField;
    }
    public function getIsSortAsc(): bool
    {
        return $this->isSortAsc === 'true';
    }
    public function getPageNumber(): int
    {
        return intval($this->pageNumber);
    }
}
