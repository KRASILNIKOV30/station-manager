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

    public function getStations(ListStationsParams $params): array
    {
        $queryParams = [];
        $query = $this->buildSqlQuery($params, $queryParams);

        $stmt = $this->connection->execute($query, $queryParams);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return array_map(fn($row) => $this->hydrateData($row), $rows);
    }

    public function buildSqlQuery(ListStationsParams $params, array &$queryParams): string
    {
        $whereConditions = [];
        foreach ($params->getFilters() as $filter) {
            $whereConditions[] = $this->buildFilterWhereCondition($filter, $queryParams);
        }
        $whereConditionsStr = count($whereConditions) > 0
            ? implode(' AND ', $whereConditions)
            : 'TRUE';

        return (<<<SQL
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
            WHERE ${whereConditionsStr}
            SQL
        );
    }

    private function buildFilterWhereCondition(StationFilter $filter, array &$queryParams): string
    {
        switch ($filter->getFilterByField()) {
            case StationFilter::FILTER_BY_ROAD:
                $queryParams[] = $filter->getValue();
                return 'r.road_name = ?';
            case StationFilter::FILTER_BY_STATION_NAME:
                $queryParams[] = $filter->getValue();
                return 's.station_name = ?';
            case StationFilter::FILTER_BY_POSITION:
                $queryParams[] = $filter->getValue();
                return 'p.position_name = ?';
            case StationFilter::FILTER_BY_PAVILION:
                $queryParams[] = $filter->getValue();
                return 's.with_pavilion = ?';
            default:
                throw new \RuntimeException("Filtering is not implemented for field {$filter->getFilterByField()}");
        }
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
