<?php

declare(strict_types=1);

namespace App\Data;

class StationFilter
{
    public const FILTER_BY_ROAD = 'road';
    public const FILTER_BY_STATION_NAME = 'station_name';
    public const FILTER_BY_POSITION = 'position';
    public const FILTER_BY_PAVILION = 'with_pavilion';

    private const ALL_FILTERS = [
        self::FILTER_BY_ROAD,
        self::FILTER_BY_STATION_NAME,
        self::FILTER_BY_POSITION,
        self::FILTER_BY_PAVILION
    ];

    private string $filterByField;
    private string $value;

    public function __construct(string $filterByField, string $value)
    {
        if (!in_array($filterByField, self::ALL_FILTERS, true)) {
            throw new \InvalidArgumentException("List cannot be filtered by field '$filterByField'");
        }
        $this->filterByField = $filterByField;
        $this->value = $value;
    }

    public function getFilterByField(): string
    {
        return $this->filterByField;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
