<?php

namespace Donchev\Framework\Service;

use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;
use Donchev\Framework\Model\User;
use Donchev\Framework\Repository\Repository;
use Exception;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class UserNotificationService
{
    /**
     * @var Repository
     */
    private $repository;

    /**
     * @var EmailService
     */
    private $emailService;

    /**
     * @var Container
     */
    private $container;

    public function __construct(Repository $repository, EmailService $emailService, Container $container)
    {
        $this->repository = $repository;
        $this->emailService = $emailService;
        $this->container = $container;
    }

    /**
     * @param User $user
     * @param int $routeId
     * @return void
     * @throws DependencyException
     * @throws LoaderError
     * @throws NotFoundException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function newMediaNotification(User $user, int $routeId)
    {
        if ($subscribers = $this->repository->getAllSubscribersButCurrentUser($user->getId())) {

            $twig = $this->container->get(Environment::class);

            $siteUrl = $this->container->get('app.settings')['site.url'];
            $siteName = $this->container->get('app.settings')['site.name'];

            $route = $this->repository->getRoutePerId($routeId);

            foreach ($subscribers as $subscriber) {

                $html = $twig->render('notification/media.html.twig', [
                    'receiver' => $subscriber['name'],
                    'sender' => $user,
                    'route' => $route,
                    'siteUrl' => $siteUrl,
                    'siteName' => $siteName,
                ]);

                $title = $subscriber['name'] . ', ' . $user->getName() . ' качи нова снимка! 😋';

                $this->emailService->sendHtmlMail(
                    $title,
                    $html,
                    $subscriber['email']
                );
            }
        }
    }

    public function newRouteNotification(User $user, int $routeId)
    {
        if ($subscribers = $this->repository->getAllSubscribersButCurrentUser($user->getId())) {

            $twig = $this->container->get(Environment::class);

            $siteUrl = $this->container->get('app.settings')['site.url'];
            $siteName = $this->container->get('app.settings')['site.name'];

            $route = $this->repository->getRoutePerId($routeId);

            foreach ($subscribers as $subscriber) {

                $html = $twig->render('notification/route.html.twig', [
                    'receiver' => $subscriber['name'],
                    'sender' => $user,
                    'route' => $route,
                    'siteUrl' => $siteUrl,
                    'siteName' => $siteName,
                ]);

                $title = $subscriber['name'] . ', ' . $user->getName() . ' качи ново трасе! 🚴‍♂️';

                $this->emailService->sendHtmlMail(
                    $title,
                    $html,
                    $subscriber['email']
                );
            }
        }
    }
}
