<?php

declare(strict_types=1);

namespace App\Data;

class ListStationsParams
{
    public const SORT_BY_ROAD = 'road';
    public const SORT_BY_DISTANCE = 'distance';
    public const SORT_BY_STATION_NAME = 'station_name';

    private const ALL_SORT_BY = [
        self::SORT_BY_ROAD,
        self::SORT_BY_DISTANCE,
        self::SORT_BY_STATION_NAME
    ];

    private string $searchQuery;
    /** @var StationFilter[] */
    private array $filters;
    private string $sortByField;
    private bool $sortAscending;
    private int $pageSize;
    private int $pageNo;


    /**
     * @param string $searchQuery
     * @param StationFilter[] $filters
     * @param string $sortByField
     * @param bool $sortAscending
     * @param int $pageSize
     * @param int $pageNo
     */
    public function __construct(
        string $searchQuery,
        array $filters,
        string $sortByField,
        bool $sortAscending,
        int $pageSize,
        int $pageNo
    ) {
        if ($pageSize <= 0) {
            throw new \InvalidArgumentException("List page size must be positive number, got $pageSize");
        }
        if ($pageNo < 1) {
            throw new \InvalidArgumentException("List page number must be positive number, got $pageNo");
        }
        if (!in_array($sortByField, self::ALL_SORT_BY, true)) {
            throw new \InvalidArgumentException("List cannot be sorted by field '$sortByField'");
        }

        $this->searchQuery = $searchQuery;
        $this->filters = $filters;
        $this->sortByField = $sortByField;
        $this->sortAscending = $sortAscending;
        $this->pageSize = $pageSize;
        $this->pageNo = $pageNo;
    }

    public function getSearchQuery(): string
    {
        return $this->searchQuery;
    }

    /**
     * @return StationFilter[]
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    public function getSortByField(): string
    {
        return $this->sortByField;
    }

    public function isSortAscending(): bool
    {
        return $this->sortAscending;
    }

    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    public function getPageNo(): int
    {
        return $this->pageNo;
    }
}
