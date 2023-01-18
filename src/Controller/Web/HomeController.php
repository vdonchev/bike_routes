<?php

namespace Donchev\Framework\Controller\Web;

use DI\DependencyException;
use DI\NotFoundException;
use Donchev\Framework\Model\Route;
use Donchev\Framework\Repository\Repository;
use Donchev\Framework\Security\Authenticator;
use Exception;
use Psr\Log\LoggerInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class HomeController extends BaseController
{
    /**
     * @throws DependencyException
     * @throws NotFoundException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function index(Repository $repository, LoggerInterface $logger, Authenticator $authenticator)
    {
        $user = $authenticator->getCurrentUser();

        $routesData = $repository->getAllRoutes();

        $routes = [];
        foreach ($routesData as $route) {
            $routes[] = new Route($route);
        }

        $lastUpdate = $routes ? $routes[0]->getCreatedAt()->format('Y/m/d H:i:s') : 'No updates yet';

        $logger->info($_SERVER['REMOTE_ADDR']);

        $this->renderTemplate('home/index.html.twig',
            ['routes' => $routes, 'last_update' => $lastUpdate, 'user' => $user]);
    }
}
