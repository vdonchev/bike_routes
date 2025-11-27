<?php

use DI\Container;
use DI\ContainerBuilder;
use Donchev\Log\Loggers\FileLogger;
use Nette\Mail\Mailer;
use Nette\Mail\SmtpMailer;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\CacheInterface;
use Twig\Environment;
use Twig\Extension\DebugExtension;

return function (array $settings) {
    $builder = new ContainerBuilder();

    $builder->useAttributes(true);

    if ($settings['app.env'] === 'prod') {
        $builder->enableCompilation(__DIR__ . '/../var/cache/container');
    }

    $builder->addDefinitions(
        [
            LoggerInterface::class => DI\create(FileLogger::class)->constructor(
                dirname(__DIR__) . '/var/log/application.log'
            ),

            'logger.for.visits' => DI\create(FileLogger::class)->constructor(
                dirname(__DIR__) . '/public/7fbd95b080fce50b0b0332c4da6cb39b.log'
            ),

            Environment::class => DI\factory(function (ContainerInterface $container) {
                $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__) . '/templates');

                $options = $container->get('app.settings')['app.env'] === 'prod'
                    ? ['cache' => dirname(__DIR__) . '/var/cache/twig'] : [];

                if ($container->get('app.settings')['app.env'] === 'dev') {
                    $options['debug'] = true;
                }

                $twig = new Environment($loader, $options);

                if ($container->get('app.settings')['app.env'] === 'dev') {
                    $twig->addExtension(new DebugExtension());
                }

                $twig->addGlobal('css_ver',
                    fileatime(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'style' . DIRECTORY_SEPARATOR . 'main.css'));

                return $twig;
            }),

            Mailer::class => DI\factory(function (Container $container) {
                $settings = $container->get('app.settings');
                return new SmtpMailer(
                    $settings['mail.host'],
                    $settings['mail.username'],
                    $settings['mail.password'],
                    (int)$settings['mail.port'],
                    $settings['mail.secure'] ?: null,
                );
            }),

            MeekroDB::class => DI\factory(function (Container $container) {
                return new MeekroDB(
                    "mysql:host={$container->get('app.settings')['db.host']};dbname={$container->get('app.settings')['db.name']}",
                    $container->get('app.settings')['db.username'],
                    $container->get('app.settings')['db.password']
                );
            }),

            CacheInterface::class => DI\create(FilesystemAdapter::class)
                ->constructor('', 0, dirname(__DIR__) . '/var/cache/filesystem'),

            'app.cache' => DI\get(CacheInterface::class),

            'app.settings' => $settings,
        ]
    );

    return $builder->build();
};
