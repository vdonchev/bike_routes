<?php

namespace Donchev\Framework\Controller\Web;

use DI\Attribute\Inject;
use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

abstract class BaseController
{
    #[Inject]
    private ?Container $container = null;

    private ?Environment $template = null;

    /**
     * @param string $templateName
     * @param array $parameters
     * @throws DependencyException
     * @throws LoaderError
     * @throws NotFoundException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function renderTemplate(string $templateName, array $parameters = []): void
    {
        echo $this->getTemplate()->render($templateName, $parameters);
    }

    /**
     * @return Environment
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function getTemplate(): Environment
    {
        if (!$this->template) {
            $this->template = $this->container->get(Environment::class);
        }

        return $this->template;
    }

    /**
     * @param string $name
     * @return string
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function getSettings(string $name): string
    {
        return $this->container->get('app.settings')[$name];
    }

    /**
     * @return AdapterInterface
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function getCache(): AdapterInterface
    {
        return $this->container->get('app.cache');
    }

    /**
     * @return Container
     */
    public function getContainer(): Container
    {
        return $this->container;
    }

    /**
     * @param string $url
     * @return void
     */
    #[NoReturn]
    public function redirect(string $url): void
    {
        header("Location: $url");
        exit();
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function logVisit(): void
    {
        $logger = $this->container->get('logger.for.visits');
        $logger->info($_SERVER['REMOTE_ADDR'] . ' => ' . $_SERVER['REQUEST_URI']);
    }
}
