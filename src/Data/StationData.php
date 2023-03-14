<?php

declare(strict_types=1);

namespace App\Data;

class StationData
{
    private int $stationId;
    private string $road;
    private int $distance;
    private string $name;
    private string $position;
    private string $withPavilion;

    public function __construct(
        int $stationId,
        string $road,
        int $distance,
        string $name,
        string $position,
        string $withPavilion
    ) {
        $this->stationId = $stationId;
        $this->road = $road;
        $this->distance = $distance;
        $this->name = $name;
        $this->position = $position;
        $this->withPavilion = $withPavilion;
    }

    public function getID(): int
    {
        return $this->stationId;
    }

    public function getRoad(): string
    {
        return $this->road;
    }

    public function getDistance(): int
    {
        return $this->distance;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPosition(): string
    {
        return $this->position;
    }

    /**
     * @return string
     */
    public function getWithPavilion(): string
    {
        return $this->withPavilion;
    }
}