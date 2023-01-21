<?php

namespace Donchev\Framework\Controller\Web;

use Donchev\Framework\Repository\Repository;
use Donchev\Framework\Security\Authenticator;
use Donchev\Framework\Service\ImageUploadService;
use Donchev\Framework\Service\NotificationService;

class MediaController extends BaseController
{
    public function uploadImage(
        ImageUploadService $uploadService,
        Authenticator $authenticator,
        Repository $repository,
        NotificationService $notificationService
    ) {
        if (!$user = $authenticator->getCurrentUser()) {
            $this->redirect('/');
        }

        $route_id = intval($_POST['route_id']);

        if ($files = $uploadService->handleMultiUpload($_FILES['image_field'])) {
            $notificationService->addSuccess('Успешен ъплоуд! 😋');
        }

        foreach ($files as $file) {
            $repository->addMedia($file, $user->getId(), $route_id);
        }

        $this->redirect('/route/' . $route_id);
    }
}
