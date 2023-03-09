<?php

declare(strict_types=1);

namespace App\Data;

use App\Common\Database\Connection;
use App\Common\Database\ConnectionProvider;

class StationDataSource
{
    private Connection $connection;
    public function __construct()
    {
        $this->connection = ConnectionProvider::getConnection();
    }

    public function getAllStations(): array
    {
        $whereConditions = $this->buildFilterWhereCondition();
        $stmt = $this->connection->execute(<<<SQL
            SELECT
                s.station_id,
                r.road_name,
                s.distance,
                s.station_name,
                p.position_name,
                bool_to_string(s.with_pavilion) AS with_pavilion
            FROM station s
                INNER JOIN road_code_name r ON s.road_code = r.road_code
                INNER JOIN position_name p ON s.position = p.position_code
            WHERE ${whereConditions}
            SQL
        );
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return array_map(fn($row) => $this->hydrateData($row), $rows);
    }

    private function buildFilterWhereCondition(): string
    {
        return "r.road_name = 'Русский Кугунур - Большой Ляждур'";
    }

    private function hydrateData(array $data): StationData
    {
        return new StationData(
            $data['station_id'],
            $data['road_name'],
            $data['distance'],
            $data['station_name'],
            $data['position_name'],
            $data['with_pavilion']
        );
    }
}
