<?php

namespace Donchev\Framework\Controller\Web;

use DI\DependencyException;
use DI\NotFoundException;
use Donchev\Framework\Repository\Repository;
use Donchev\Framework\Security\Authenticator;
use Donchev\Framework\Service\MediaService;
use Donchev\Framework\Service\SiteNotificationService;
use Donchev\Framework\Service\UserNotificationService;
use Exception;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class RouteController extends NotificationAwareController
{
    /**
     * @param int $id
     * @param Repository $repository
     * @param Authenticator $authenticator
     * @return void
     * @throws DependencyException
     * @throws LoaderError
     * @throws NotFoundException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function route(
        int $id,
        Repository $repository,
        Authenticator $authenticator
    ) {
        $this->logVisit();

        if (!$route = $repository->getRoutePerId($id)) {
            $this->redirect('/');
        }

        $user = $authenticator->getCurrentUser();
        $media = $repository->getMediaPerRouteId($route->getId());

        $this->renderTemplate('/route/route.html.twig',
            ['route' => $route, 'user' => $user, 'media' => $media]);
    }

    /**
     * @param Authenticator $authenticator
     * @return void
     * @throws DependencyException
     * @throws LoaderError
     * @throws NotFoundException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function add(Authenticator $authenticator)
    {
        $this->logVisit();

        if (!$authenticator->isAdmin()) {
            $this->redirect('/');
        }

        $user = $authenticator->getCurrentUser();

        $this->renderTemplate('/route/add.html.twig', ['user' => $user]);
    }

    public function addSubmit(
        MediaService $mediaService,
        Repository $repository,
        SiteNotificationService $notificationService,
        UserNotificationService $userNotificationService,
        Authenticator $authenticator
    ) {
        if (isset(
            $_POST['route_name'],
            $_POST['route_url'],
            $_POST['route_difficulty'],
            $_POST['route_length'],
            $_POST['route_ascent'],
            $_FILES['route_map'],
            $_FILES['route_gpx']
        )) {
            if (!$user = $authenticator->getCurrentUser()) {
                $this->redirect('/');
            }

            $name = trim($_POST['route_name']);
            $url = trim($_POST['route_url']);
            $difficulty = intval($_POST['route_difficulty']);
            $length = floatval($_POST['route_length']);
            $ascent = intval($_POST['route_ascent']);
            $map = $_FILES['route_map'];
            $gpx = $_FILES['route_gpx'];

            $note = $_POST['route_notes'] ?? null;
            $strava_url = $_POST['route_strava_url'] ?? null;
            $isRace = isset($_POST['route_is_race']);

            $mapUpload = $mediaService->uploadFile(
                $map, $this->getContainer()->get('app.settings')['media.map.path'], null, true
            );

            $gpxUpload = $mediaService->uploadFile(
                $gpx, $this->getContainer()->get('app.settings')['media.gpx.path'], null, true, 'gpx'
            );

            if ($mapUpload && $gpxUpload) {
                if ($routeId = $repository->addRoute(
                    $name, $mapUpload, $gpxUpload, $url, $difficulty, $length, $ascent, $note, $strava_url, $isRace
                )) {
                    $notificationService->addSuccess('Трасето е качено успешно!');

                    if (isset($_POST['route_notification'])) {
                        $userNotificationService->newRouteNotification($user, $routeId);
                    }
                }
            }
        }

        $this->redirect('/');
    }
}
