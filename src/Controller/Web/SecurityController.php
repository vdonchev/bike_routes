<?php

namespace Donchev\Framework\Controller\Web;

use DI\DependencyException;
use DI\NotFoundException;
use Donchev\Framework\Security\Authenticator;
use Exception;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class SecurityController extends BaseController
{
    /**
     * @throws SyntaxError
     * @throws NotFoundException
     * @throws RuntimeError
     * @throws LoaderError
     * @throws DependencyException
     */
    public function login(Authenticator $authenticator)
    {
        if ($authenticator->getCurrentUser()) {
            $this->redirect('/');
        }

        $this->renderTemplate('security/login.html.twig');
    }

    /**
     * @throws Exception
     */
    public function doLogin(Authenticator $authenticator)
    {
        if (
            isset($_POST['username'])
            && isset($_POST['password'])
            && !empty($_POST['username'])
            && !empty($_POST['password'])) {

            if ($authenticator->login($_POST['username'], $_POST['password'])) {
                $this->redirect('/');
            }
        }

        $this->redirect('/login');
    }

    public function logout(Authenticator $authenticator)
    {
        $authenticator->logout();

        $this->redirect('/');
    }
}
