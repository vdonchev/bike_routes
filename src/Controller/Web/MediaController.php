<?php

namespace Donchev\Framework\Controller\Web;

use Donchev\Framework\Repository\Repository;
use Donchev\Framework\Security\Authenticator;
use Donchev\Framework\Service\MediaService;
use Donchev\Framework\Service\NotificationService;

class MediaController extends BaseController
{

    /**
     * @var NotificationService
     */
    private $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function uploadImage(
        MediaService $uploadService,
        Authenticator $authenticator,
        Repository $repository
    ) {
        if (!$user = $authenticator->getCurrentUser()) {
            $this->redirect('/');
        }

        $route_id = intval($_POST['route_id']);

        if ($files = $uploadService->handleImageMultiUpload($_FILES['image_field'])) {
            $this->notificationService->addSuccess('Успешен ъплоуд! 😋');
        }

        foreach ($files as $file) {
            $repository->addMedia($file, $user->getId(), $route_id);
        }

        $this->redirect('/route/' . $route_id);
    }

    public function deleteMedia(int $mediaId, int $routeId, Authenticator $authenticator, MediaService $mediaService)
    {
        if ($mediaService->deleteImage($mediaId, $authenticator->getCurrentUser()->getId())) {
            $this->notificationService->addSuccess('Туй то! Снимката е изтрита успешно!');
        }

        $this->redirect('/route/' . $routeId);
    }
}
