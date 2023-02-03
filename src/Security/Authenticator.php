<?php

namespace Donchev\Framework\Security;

use DateTime;
use Donchev\Framework\Model\User;
use Donchev\Framework\Repository\Repository;
use Donchev\Framework\Service\SiteNotificationService;
use Exception;

class Authenticator
{
    /**
     * @var Repository
     */
    private $repository;

    /**
     * @var SiteNotificationService
     */
    private $notificationService;

    /**
     * @param Repository $repository
     */
    public function __construct(Repository $repository, SiteNotificationService $notificationService)
    {
        $this->repository = $repository;
        $this->notificationService = $notificationService;
    }

    /**
     * @throws Exception
     */
    public function login(string $username, string $plainPassword, bool $remember = false): bool
    {
        $username = $this->sanitize($username);
        $plainPassword = $this->sanitize($plainPassword);

        $dbUser = $this->repository->getUserPerUsername($username);

        if ($dbUser) {

            if (password_verify($plainPassword, $dbUser['password'])) {
                $this->performLogin($dbUser, $remember);

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
        $currentUser = $this->getCurrentUser();

        /** delete remember me tokens */
        $this->repository->removeTokensPerUser($currentUser->getId());

        /** delete remember me cookie */
        setcookie('remember_me', '', 1);

        /** delete session */
        session_unset();

        session_regenerate_id(true);
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

        if (!password_verify($currentPassword, $user->getPasswordHash())) {
            $this->notificationService->addError('Грешка 😶. Текущата парола не е вярна.');
            return false;
        }

        if ($newPassword != $newPasswordRepeat) {
            $this->notificationService->addError('Ох... Двете нови пароли не съвпадат!');
            return false;
        }

        if (!preg_match("/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*\W)(?!.* ).{8,}$/", $newPassword)) {
            $this->notificationService->addError('Новата парола не отговаря на минималните изисквания');
            return false;
        }

        $this->repository->updateUserPassword($user->getId(), password_hash($newPassword, PASSWORD_DEFAULT));
        $this->logout();

        $this->notificationService->addSuccess('Ехааа!! Успешно смени паролата си.');

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

    /**
     * @throws Exception
     */
    public function isUserRemembered()
    {
        if ($this->getCurrentUser()) {
            return;
        }

        if (isset($_COOKIE['remember_me']) && !empty($_COOKIE['remember_me'])) {
            $token = trim($_COOKIE['remember_me']);

            if ($dbToken = $this->repository->getToken($token)) {
                $expiry = new DateTime($dbToken['expiry']);

                if ($expiry > new DateTime()) {
                    $userData = $this->repository->getUserPerId($dbToken['user_id']);

                    $this->performLogin($userData);
                }
            }
        }
    }

    /**
     * @param array $dbUser
     * @param bool $remember
     * @return void
     * @throws Exception
     */
    private function performLogin(array $dbUser, bool $remember = false)
    {
        $user = new User($dbUser);

        $_SESSION['user'] = serialize($user);

        if ($remember) {
            $token = $this->generateToken();

            $this->repository->storeToken($token, $user->getId());
            $this->setRememberCookie($token);
        }
    }

    private function setRememberCookie(string $token)
    {
        setcookie('remember_me', $token, time() + 60 * 60 * 24 * 30);
    }

    /**
     * @throws Exception
     */
    private function generateToken(): string
    {
        return bin2hex(random_bytes(32));
    }
}
