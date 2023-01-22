<?php

namespace Donchev\Framework\Service;

use DI\Container;
use Donchev\Framework\Model\User;
use Donchev\Framework\Repository\Repository;
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

    public function __construct(Repository $repository, Container $container)
    {
        $this->repository = $repository;
        $this->container = $container;
    }

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
