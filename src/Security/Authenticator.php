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

    /**
     * @param Repository $repository
     */
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

    /**
     * @return void
     */
    public function logout()
    {
        session_unset();
    }

    /**
     * @return User|null
     */
    public function getCurrentUser(): ?User
    {
        if (isset($_SESSION)) {
            return isset($_SESSION['user']) ? unserialize($_SESSION['user']) : null;
        }

        return null;
    }

    /**
     * @param string $currentPassword
     * @param string $newPassword
     * @param string $newPasswordRepeat
     * @param User $user
     * @return bool
     */
    public function passwordUpdate(
        string $currentPassword,
        string $newPassword,
        string $newPasswordRepeat,
        User $user
    ): bool {
        $currentPassword = $this->sanitize($currentPassword);
        $newPassword = $this->sanitize($newPassword);
        $newPasswordRepeat = $this->sanitize($newPasswordRepeat);

        if ($newPassword != $newPasswordRepeat) {
            return false;
        }

        if (!preg_match("/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*\W)(?!.* ).{8,}$/", $newPassword)) {
            return false;
        }

        if (!password_verify($currentPassword, $user->getPasswordHash())) {
            return false;
        }

        $this->repository->updateUserPassword($user->getId(), password_hash($newPassword, PASSWORD_DEFAULT));
        $this->logout();

        return true;
    }

    /**
     * @param string $data
     * @return string
     */
    private function sanitize(string $data): string
    {
        return trim($data);
    }

    public function isAdmin(): bool
    {
        if ($user = $this->getCurrentUser()) {
            return $user->getId() === 1;
        }

        return false;
    }
}
