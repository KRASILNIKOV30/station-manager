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
    public function table(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $formData = StationsFormData::fromArray($request->getQueryParams());
        $listParams = $this->getListLimitationParams($formData);
        $dataSource = new StationDataSource();
        $stations = $dataSource->getStations($listParams);
        $view = Twig::fromRequest($request);
        /* XDEBUG + расширение для браузера
         * $logger = new Logger('stderr');
        $logger->pushHandler(new StreamHandler('php://stderr', Logger::WARNING));
        $logger->info(print_r(array_map(fn($station) => $this->getRowData($station), $stations), false));*/

        return $view->render($response, "stations_page.twig", [
            'stations' => array_map(fn($station) => $this->getRowData($station), $stations)
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
            ListStationsParams::SORT_BY_STATION_NAME,
            true,
            10,
            1
        );
    }

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
