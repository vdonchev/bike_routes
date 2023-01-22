<?php

namespace Donchev\Framework\Controller\Web;

use Donchev\Framework\Security\Authenticator;
use Donchev\Framework\Service\MediaService;

class MediaController extends NotificationAwareController
{
    public function uploadImage(
        MediaService $uploadService,
        Authenticator $authenticator
    ) {
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
        if ($mediaService->deleteImage($mediaId, $authenticator->getCurrentUser()->getId())) {
            $this->getNotificationService()->addSuccess('Туй то! Снимката е изтрита успешно!');
        }

        $this->redirect('/route/' . $routeId);
    }
}
