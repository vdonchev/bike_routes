<?php

namespace Donchev\Framework\Controller\Web;

use DI\DependencyException;
use DI\NotFoundException;
use Donchev\Framework\Model\User;
use Donchev\Framework\Security\Authenticator;
use Exception;
use JetBrains\PhpStorm\NoReturn;
use MeekroDBException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class SecurityController extends NotificationAwareController
{

    private ?Authenticator $authenticator = null;

    public function __construct(Authenticator $authenticator)
    {
        $this->authenticator = $authenticator;
        parent::__construct($authenticator);
    }

    /**
     * @return void
     * @throws Exception
     */
    #[NoReturn]
    public function doLogin(): void
    {
        if ($this->currentUserExists()) {
            $this->redirect('/');
        }

        if (
            !empty($_POST['username']) && !empty($_POST['password'])
        ) {
            $remember = !empty($_POST['remember']);

            if ($this->authenticator->login($_POST['username'], $_POST['password'], $remember)) {
                $this->getNotificationService()->addSuccess('Хей! Успешен вход!');
                $this->redirect('/');
            }
        }

        $this->getNotificationService()->addError('Ох, нещо се обърка. Опитай пак 😕');
        $this->redirect('/login');
    }

    /**
     * @return User|null
     */
    private
    function currentUserExists(): ?User
    {
        return $this->authenticator->getCurrentUser();
    }

    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function login(): void
    {
        $this->logVisit();

        if ($this->currentUserExists()) {
            $this->redirect('/');
        }

        $this->renderTemplate('security/login.html.twig');
    }

    /**
     * @return void
     */
    #[NoReturn]
    public function logout(): void
    {
        try {
            $this->logVisit();
        } catch (DependencyException|NotFoundException $e) {
        }

        $this->authenticator->logout();

        $this->getNotificationService()->addSuccess('Успешен изход!');

        $this->redirect('/');
    }

    /**
     * @return void
     * @throws DependencyException
     * @throws LoaderError
     * @throws NotFoundException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function profile(): void
    {
        $this->logVisit();

        if (!$user = $this->currentUserExists()) {
            $this->redirect('/');
        }

        $this->renderTemplate('security/profile.html.twig', ['user' => $user]);
    }

    /**
     * @return void
     * @throws DependencyException
     * @throws LoaderError
     * @throws NotFoundException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function password(): void
    {
        if (!$user = $this->currentUserExists()) {
            $this->redirect('/');
        }

        $this->renderTemplate('security/password.html.twig', ['user' => $user]);
    }

    /**
     * @return void
     * @throws MeekroDBException
     */
    #[NoReturn]
    public function passwordUpdate(): void
    {
        if (!$user = $this->currentUserExists()) {
            $this->redirect('/');
        }

        if (
            !empty($_POST['current-password']) && !empty($_POST['new-password']) && !empty($_POST['new-password-repeat'])
        ) {
            if ($this->authenticator->passwordUpdate(
                $_POST['current-password'],
                $_POST['new-password'],
                $_POST['new-password-repeat'],
                $user
            )) {
                $this->redirect('/profile');
            }
        }

        $this->redirect('/password');
    }
}
