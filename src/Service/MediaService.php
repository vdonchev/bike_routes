<?php

namespace Donchev\Framework\Service;

use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;
use Donchev\Framework\Model\User;
use Donchev\Framework\Repository\Repository;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Verot\Upload\Upload;

class MediaService
{
    /**
     * @var Repository
     */
    private $repository;

    /**
     * @var Container
     */
    private $container;

    /**
     * @var UserNotificationService
     */
    private $userNotificationService;

    public function __construct(
        Repository $repository,
        Container $container,
        UserNotificationService $userNotificationService
    ) {
        $this->repository = $repository;
        $this->container = $container;
        $this->userNotificationService = $userNotificationService;
    }

    /**
     * @throws SyntaxError
     * @throws NotFoundException
     * @throws RuntimeError
     * @throws DependencyException
     * @throws LoaderError
     */
    public function handleImageMultiUpload(array $payload, User $user, int $routeId): bool
    {
        $files = [];
        foreach ($payload as $k => $l) {
            foreach ($l as $i => $v) {
                if (!array_key_exists($i, $files)) {
                    $files[$i] = [];
                }
                $files[$i][$k] = $v;
            }
        }

        $uploadedFiles = [];

        foreach ($files as $file) {
            $handle = new Upload($file);
            if ($handle->uploaded) {
                $handle->file_new_name_body = uniqid();
                $handle->process(
                    $this->container->get('app.settings')['media.path']
                );

                if ($handle->processed) {
                    $uploadedFiles[] = $handle->file_dst_name;
                    $handle->clean();
                } else {
                    return false;
                }
            }
        }

        foreach ($uploadedFiles as $file) {
            $this->repository->addMedia($file, $user->getId(), $routeId);
        }

        if (count($uploadedFiles) > 0) {
            $this->userNotificationService->newMediaNotification($user, $routeId);
        }

        return true;
    }

    public function deleteImage(int $mediaId, int $authorId): bool
    {
        /** delete file */
        $media = $this->repository->getMediaRowPerId($mediaId);

        if (is_file($this->container->get('app.settings')['media.path'] . DIRECTORY_SEPARATOR . $media['file'])) {
            unlink($this->container->get('app.settings')['media.path'] . DIRECTORY_SEPARATOR . $media['file']);

            /** delete from db */
            $this->repository->deleteMedia($mediaId, $authorId);

            return true;
        }

        return false;
    }
}
