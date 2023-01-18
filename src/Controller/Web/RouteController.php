<?php

namespace Donchev\Framework\Controller\Web;

use Donchev\Framework\Repository\Repository;

class RouteController extends BaseController
{
    public function route(int $id, Repository $repository)
    {
        $route = $repository->getRoutePerId($id);
        var_dump($route);
    }
}