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

    /**
     * @param ListStationsParams $params
     * @return array
     */
    public function getStations(ListStationsParams $params): array
    {
        $queryParams = [];
        $query = $this->buildSqlQuery($params, $queryParams);

        $stmt = $this->connection->execute($query, $queryParams);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return array_map(fn($row) => $this->hydrateData($row), $rows);
    }

    /**
     * @return array
     */
    public function getStationsRoadOptions(): array
    {
        $stmt = $this->connection->execute(<<<SQL
            SELECT
                road_code,
                road_name
            FROM road_code_name
        SQL);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return array_combine(
            array_column($rows, 'road_code'),
            array_column($rows, 'road_name')
        );
    }

    /**
     * @return array
     */
    public function getStationsPositionOptions(): array
    {
        $stmt = $this->connection->execute(<<<SQL
            SELECT
                position_code,
                position_name
            FROM position_name
        SQL);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return array_combine(
            array_column($rows, 'position_code'),
            array_column($rows, 'position_name')
        );
    }

    public function getRowsAmount(): int
    {
        $stmt = $this->connection->execute(<<<SQL
            SELECT FOUND_ROWS()
        SQL);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC)[0]["FOUND_ROWS()"];
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
        $whereSearchConditions = $this->buildSearchWhereCondition($params->getSearchQuery(), $queryParams);
        $whereConditionsStr .= ' AND (' . implode(' OR ', $whereSearchConditions) . ')';
        $sortByField = $this->buildOrderByValue($params->getSortByField());
        $order = $params->isSortAscending() ? 'ASC' : 'DESC';
        $pageSize = $params->getPageSize();
        $pageNumber = $params->getPageNumber();
        $offset = $pageSize * ($pageNumber - 1);
        return (<<<SQL
            SELECT SQL_CALC_FOUND_ROWS
                s.station_id,
                r.road_name,
                s.distance,
                s.station_name,
                p.position_name,
                bool_to_string(s.with_pavilion) AS with_pavilion
            FROM station s
                INNER JOIN road_code_name r ON s.road_code = r.road_code
                INNER JOIN position_name p ON s.position = p.position_code
            WHERE {$whereConditionsStr}
            GROUP BY s.station_id 
            ORDER BY {$sortByField} {$order}
            LIMIT {$pageSize} OFFSET {$offset}
            SQL
        );
    }

    private function buildSearchWhereCondition(string $searchQuery, &$queryParams): array
    {
        $columnsToSearch = ['r.road_name', 's.distance', 's.station_name'];
        $result = [];
        foreach ($columnsToSearch as $column) {
            $queryParams[] = "%{$searchQuery}%";
            $result[] = "{$column} LIKE ?";
        }

        return $result;
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
                $queryParams[] = $filter->getValue() == "Есть";
                return 's.with_pavilion = ?';
            default:
                throw new \RuntimeException("Filtering is not implemented for field {$filter->getFilterByField()}");
        }
    }

    private function buildOrderByValue(string $sortByField): string
    {
        switch ($sortByField) {
            case ListStationsParams::SORT_BY_ROAD:
                return 'r.road_name';
            case ListStationsParams::SORT_BY_DISTANCE:
                return 's.distance';
            case ListStationsParams::SORT_BY_STATION_NAME:
                return 's.station_name';
            default:
                throw new \RuntimeException("Ordering is not implementing for field {$sortByField}");
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
