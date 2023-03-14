<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\Data\StationsFormData;
use App\Data\ListStationsParams;
use App\Data\StationData;
use App\Data\StationDataSource;
use App\Data\StationFilter;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Views\Twig;

class StationsController
{
    private const PAGE_SIZE = 25;
    public function table(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $formData = StationsFormData::fromArray($request->getQueryParams());
        $listParams = $this->getListLimitationParams($formData);
        $dataSource = new StationDataSource();
        $stations = $dataSource->getStations($listParams);
        $roadOptions = $dataSource->getStationsRoadOptions();
        $positionOptions = $dataSource->getStationsPositionOptions();
        $view = Twig::fromRequest($request);

        return $view->render($response, "stations_page.twig", [
            'stations' => array_map(fn($station) => $this->getRowData($station), $stations),
            'form_values' => $formData->toArray(),
            'road_options' => $roadOptions,
            'position_options' => $positionOptions,
            'sort_by_field' => $listParams->getSortByField(),
        ]);
    }

    private function getListLimitationParams(StationsFormData $data): ListStationsParams
    {
        $filters = [];
        if ($value = $data->getFilterByRoad()) {
            $filters[] = new StationFilter(StationFilter::FILTER_BY_ROAD, $value);
        }
        if ($value = $data->getFilterByStationName()) {
            $filters[] = new StationFilter(StationFilter::FILTER_BY_STATION_NAME, $value);
        }
        if ($value = $data->getFilterByPosition()) {
            $filters[] = new StationFilter(StationFilter::FILTER_BY_POSITION, $value);
        }
        if ($value = $data->getFilterByPavilion()) {
            $filters[] = new StationFilter(StationFilter::FILTER_BY_PAVILION, $value);
        }
        return new ListStationsParams(
            '',
            $filters,
            $data->getOrderByField(),
            $data->getIsSortAsc(),
            self::PAGE_SIZE,
            1
        );
    }

    /**
     * @param StationData $data
     * @return array
     */
    private function getRowData(StationData $data): array
    {
        return [
            'id' => $data->getID(),
            'road' => $data->getRoad(),
            'distance' => $data->getDistance(),
            'name' => $data->getName(),
            'position' => $data->getPosition(),
            'with_pavilion' => $data->getWithPavilion()
        ];
    }
}
