<?php

namespace Donchev\Framework\Controller\Web;

use diversen\sendfile;
use Donchev\Framework\Model\Route;
use Donchev\Framework\Repository\Repository;
use Exception;

class DownloadController extends BaseController
{
    /**
     * @param int $id
     * @param Repository $repository
     * @return void
     * @throws Exception
     */
    public function gpx(int $id, Repository $repository)
    {
        $route = $repository->getRoutePerId($id);
        $route = new Route($route);

        $sf = new sendfile();
        $file = dirname(__DIR__, 3)
            . DIRECTORY_SEPARATOR . 'public'
            . DIRECTORY_SEPARATOR . 'gpx'
            . DIRECTORY_SEPARATOR . $route->getGpxFileName();

        $sf->send($file);
    }
}
