<?php

namespace Donchev\Framework\Security;

use Donchev\Framework\Model\User;
use Donchev\Framework\Repository\Repository;
use Exception;

class Authenticator
{
    /**
     * @var Repository
     */
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws Exception
     */
    public function login(string $username, string $plainPassword): bool
    {
        $username = $this->sanitize($username);
        $plainPassword = $this->sanitize($plainPassword);

        $dbUser = $this->repository->getUserPerUsername($username);

        if ($dbUser) {

            if (password_verify($plainPassword, $dbUser['password'])) {
                $user = new User($dbUser);

                $_SESSION['user'] = serialize($user);

                return true;
            }

        }

        return false;
    }

    public function logout()
    {
        session_destroy();
    }

    public function getCurrentUser(): ?User
    {
        if (isset($_SESSION)) {
            return isset($_SESSION['user']) ? unserialize($_SESSION['user']) : null;
        }

        return null;
    }

    private function sanitize(string $data): string
    {
        return trim($data);
    }
}
