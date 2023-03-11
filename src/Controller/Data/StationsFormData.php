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

    public function __construct(
        ?string $filterByRoad,
        ?string $filterByStationName,
        ?string $filterByPosition,
        ?string $filterByPavilion,
        string $searchQuery
    ) {
        $this->filterByRoad = $filterByRoad;
        $this->filterByStationName = $filterByStationName;
        $this->filterByPosition = $filterByPosition;
        $this->filterByPavilion = $filterByPavilion;
        $this->searchQuery = $searchQuery;
    }
    public function toArray(): array
    {
        return [
            'filter_by_road' => $this->filterByRoad,
            'filter_by_station_name' => $this->filterByStationName,
            'filter_by_position' => $this->filterByPosition,
            'filter_by_pavilion' => $this->filterByPavilion,
            'search_query' => $this->searchQuery
        ];
    }
    public static function fromArray(array $parameters): self
    {
        return new self(
            $parameters['filter_by_road'] ?: null,
            $parameters['filter_by_station_name'] ?: null,
            $parameters['filter_by_position'] ?: null,
            $parameters['filter_by_pavilion'] ?: null,
            $parameters['search_query'] ?? ''
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
}
