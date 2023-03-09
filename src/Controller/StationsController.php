<?php

declare(strict_types=1);

namespace App\Controller;

use App\Data\StationData;
use App\Data\StationDataSource;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Views\Twig;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;



class StationsController
{

    public function table(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $dataSource = new StationDataSource();
        $stations = $dataSource->getAllStations();
        $view = Twig::fromRequest($request);
        /* XDEBUG + расширение для браузера
         * $logger = new Logger('stderr');
        $logger->pushHandler(new StreamHandler('php://stderr', Logger::WARNING));
        $logger->info(print_r(array_map(fn($station) => $this->getRowData($station), $stations), false));*/

        return $view->render($response, "stations_page.twig", [
            'stations' => array_map(fn($station) => $this->getRowData($station), $stations)
        ]);
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