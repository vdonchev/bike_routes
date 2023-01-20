<?php

namespace Donchev\Framework\Controller\Web;

use DI\DependencyException;
use DI\NotFoundException;
use Donchev\Framework\Repository\Repository;
use Donchev\Framework\Security\Authenticator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class RouteController extends BaseController
{
    /**
     * @param int $id
     * @param Repository $repository
     * @param Authenticator $authenticator
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function route(int $id, Repository $repository, Authenticator $authenticator)
    {
        $route = $repository->getRoutePerId($id);
        $user = $authenticator->getCurrentUser();

        $this->renderTemplate('/route/route.html.twig', ['route' => $route, 'user' => $user]);
    }
}