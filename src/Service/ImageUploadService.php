<?php

namespace Donchev\Framework\Service;

use Verot\Upload\Upload;

class ImageUploadService
{
    public function handleMultiUpload(array $payload): ?array
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
                    dirname(__DIR__, 2)
                    . DIRECTORY_SEPARATOR . 'public'
                    . DIRECTORY_SEPARATOR . 'gallery'
                );

                if ($handle->processed) {
                    $uploadedFiles[] = $handle->file_dst_name;
                    $handle->clean();
                } else {
                    return null;
                }
            }
        }

        return $uploadedFiles;
    }
}
