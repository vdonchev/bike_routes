<?php

namespace Donchev\Framework\Controller\Web;

use DI\DependencyException;
use DI\NotFoundException;
use Donchev\Framework\Repository\Repository;
use Donchev\Framework\Security\Authenticator;
use Exception;
use MeekroDBException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class HomeController extends NotificationAwareController
{
    /**
     * @throws DependencyException
     * @throws NotFoundException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function index(
        Repository $repository,
        Authenticator $authenticator
    ): void {
        $this->logVisit();

        $user = $authenticator->getCurrentUser();

        $routes = $repository->getAllRoutes();
        $latestRoutes = $repository->getAllLatestRoutes();

        $lastUpdate = $routes ? $routes[0]->getCreatedAt()->format('d/M/Y H:i:s') : 'No updates yet';

        $this->renderTemplate(
            'home/index.html.twig',
            [
                'routes' => $routes,
                'latest_routes' => $latestRoutes,
                'last_update' => $lastUpdate,
                'user' => $user
            ]
        );
    }

    /**
     * @throws SyntaxError
     * @throws MeekroDBException
     * @throws NotFoundException
     * @throws RuntimeError
     * @throws LoaderError
     * @throws DependencyException
     */
    public function races(
        Repository $repository,
        Authenticator $authenticator
    ): void {
        $this->logVisit();

        $user = $authenticator->getCurrentUser();

        $routes = $repository->getAllRaceRoutes();

        $lastUpdate = $routes ? $routes[0]->getCreatedAt()->format('d/M/Y H:i:s') : 'No updates yet';

        $this->renderTemplate(
            'home/races.html.twig',
            [
                'routes' => $routes,
                'last_update' => $lastUpdate,
                'user' => $user
            ]
        );
    }
}
