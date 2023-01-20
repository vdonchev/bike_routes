<?php

namespace Donchev\Framework\Controller\Web;

use DI\DependencyException;
use DI\NotFoundException;
use Donchev\Framework\Model\User;
use Donchev\Framework\Security\Authenticator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class SecurityController extends BaseController
{
    /**
     * @var Authenticator
     */
    private $authenticator;

    /**
     * @param Authenticator $authenticator
     */
    public function __construct(Authenticator $authenticator)
    {
        $this->authenticator = $authenticator;
    }

    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function login()
    {
        if ($this->currentUserExists()) {
            $this->redirect('/');
        }

        $this->renderTemplate('security/login.html.twig');
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function doLogin()
    {
        if ($this->currentUserExists()) {
            $this->redirect('/');
        }

        if (
            isset($_POST['username'], $_POST['password'])
            && !empty($_POST['username']) && !empty($_POST['password'])
        ) {

            if ($this->authenticator->login($_POST['username'], $_POST['password'])) {
                $this->redirect('/');
            }
        }

        $this->redirect('/login');
    }

    /**
     * @return void
     */
    public function logout()
    {
        $this->authenticator->logout();

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
    public function profile()
    {
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
    public function password()
    {
        if (!$user = $this->currentUserExists()) {
            $this->redirect('/');
        }

        $this->renderTemplate('security/password.html.twig', ['user' => $user]);
    }

    /**
     * @return void
     */
    public function passwordUpdate()
    {
        if (!$user = $this->currentUserExists()) {
            $this->redirect('/');
        }

        if (
            isset($_POST['current-password'], $_POST['new-password'], $_POST['new-password-repeat'])
            && !empty($_POST['current-password']) && !empty($_POST['new-password']) && !empty($_POST['new-password-repeat'])
        ) {
            if ($this->authenticator->passwordUpdate(
                $_POST['current-password'], $_POST['new-password'], $_POST['new-password-repeat'], $user
            )) {
                $this->redirect('/profile');
            }
        }

        $this->redirect('/password');
    }

    /**
     * @return User|null
     */
    private
    function currentUserExists(): ?User
    {
        return $this->authenticator->getCurrentUser();
    }
}
