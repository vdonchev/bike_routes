<?php

namespace Donchev\Framework\Controller\Api;

use DI\DependencyException;
use DI\NotFoundException;
use Donchev\Framework\Controller\Web\BaseController;
use Donchev\Framework\Exception\AppException;
use JetBrains\PhpStorm\NoReturn;

abstract class BaseApiController extends BaseController
{
    /**
     * @throws AppException
     */
    public function authorizeApiCall()
    {
        $auth_user = $_SERVER['PHP_AUTH_USER'] ?? '';
        $auth_pass = $_SERVER['PHP_AUTH_PW'] ?? '';

        try {
            if (!$this->validateAuthentication($auth_user, $auth_pass)) {
                throw new AppException('Authentication failed.');
            }
        } catch (DependencyException|NotFoundException|AppException $e) {
        }

        return $this->getPayload();
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    private function validateAuthentication(string $user, string $pass): bool
    {
        return hash_equals($this->getContainer()->get('app.settings')['api.username'], $user) &&
            hash_equals($this->getContainer()->get('app.settings')['api.password'], $pass);
    }

    /**
     * @throws AppException
     */
    private function getPayload()
    {
        $payload = file_get_contents('php://input');

        if (!$data = json_decode($payload, true)) {
            throw new AppException('Invalid payload.');
        }

        return $data;
    }

    #[NoReturn]
    public function sendJsonResponse($payload): void
    {
        header('Content-Type: application/json');
        echo json_encode($payload);
        die;
    }
}
