<?php

namespace Donchev\Framework\Controller\Web;

use Donchev\Framework\Repository\Repository;
use Donchev\Framework\Security\Authenticator;

class RouteController extends BaseController
{
    public function route(int $id, Repository $repository, Authenticator $authenticator)
    {
        $route = $repository->getRoutePerId($id);
        $user = $authenticator->getCurrentUser();

        $this->renderTemplate('/route/route.html.twig', ['route' => $route, 'user' => $user]);
    }
}