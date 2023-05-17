<?php

namespace Donchev\Framework\Controller\Web;

use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;
use diversen\sendfile;
use Donchev\Framework\Repository\Repository;
use Donchev\Framework\Security\Authenticator;
use Donchev\Framework\Service\MediaService;
use Exception;

class MediaController extends NotificationAwareController
{
    public function uploadImage(
        MediaService $uploadService,
        Authenticator $authenticator
    ) {
        $this->logVisit();

        if (!$user = $authenticator->getCurrentUser()) {
            $this->redirect('/');
        }

        $routeId = intval($_POST['route_id']);

        if ($uploadService->handleImageMultiUpload($_FILES['image_field'], $user, $routeId)) {
            $this->getNotificationService()->addSuccess('Успешен ъплоуд! 😋');
        } else {
            $this->getNotificationService()->addError('Ох, ох... Нещо се обърка. Опитай пак.');
        }

        $this->redirect('/route/' . $routeId);
    }

    public function deleteMedia(int $mediaId, int $routeId, Authenticator $authenticator, MediaService $mediaService)
    {
        $this->logVisit();

        if ($mediaService->deleteImage($mediaId, $authenticator->getCurrentUser()->getId())) {
            $this->getNotificationService()->addSuccess('Туй то! Снимката е изтрита успешно!');
        }

        $this->redirect('/route/' . $routeId);
    }

    /**
     * @param int $id
     * @param Repository $repository
     * @param Container $container
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     * @throws Exception
     */
    public function downloadGpx(int $id, Repository $repository, Container $container)
    {
        $this->logVisit();

        $route = $repository->getRoutePerId($id);

        $sf = new sendfile();
        $sf->contentType('application/gpx+xml');

        $file = $container->get('app.settings')['media.gpx.path'] . DIRECTORY_SEPARATOR . $route->getGpxFileName();

        $sf->send($file);
    }
}
