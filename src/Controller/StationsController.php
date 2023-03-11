<?php

declare(strict_types=1);

namespace App\Controller;

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
        $dataSource = new StationDataSource();
        $listParams = $this->getListLimitationParams();
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

    private function getListLimitationParams(): ListStationsParams
    {
        return new ListStationsParams(
            '',
            [
                new StationFilter(StationFilter::FILTER_BY_STATION_NAME, 'Большая Ноля'),
                new StationFilter(StationFilter::FILTER_BY_POSITION, 'Слева')
            ],
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
