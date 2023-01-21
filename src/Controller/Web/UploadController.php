<?php

namespace Donchev\Framework\Controller\Web;

use Donchev\Framework\Repository\Repository;
use Donchev\Framework\Security\Authenticator;
use Donchev\Framework\Service\ImageUploadService;

class UploadController extends BaseController
{
    public function uploadImage(ImageUploadService $uploadService, Authenticator $authenticator, Repository $repository)
    {
        if (!$user = $authenticator->getCurrentUser()) {
            $this->redirect('/');
        }

        $route_id = intval($_POST['route_id']);

        $files = $uploadService->handleMultiUpload($_FILES['image_field']);

        foreach ($files as $file) {
            $repository->addMedia($file, $user->getId(), $route_id);
        }

        $this->redirect('/route/' . $route_id);
    }
}
