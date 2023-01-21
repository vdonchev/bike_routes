<?php

namespace Donchev\Framework\Controller\Web;

use DI\DependencyException;
use DI\NotFoundException;
use Donchev\Framework\Model\Route;
use Donchev\Framework\Repository\Repository;
use Donchev\Framework\Security\Authenticator;
use Exception;
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
     * @throws Exception
     */
    public function route(int $id, Repository $repository, Authenticator $authenticator)
    {
        if (!$route = $repository->getRoutePerId($id)) {
            $this->redirect('/');
        }

        $route = new Route($route);
        $user = $authenticator->getCurrentUser();

        $this->renderTemplate('/route/route.html.twig', ['route' => $route, 'user' => $user]);
    }
}