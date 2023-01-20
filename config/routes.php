<?php

use Donchev\Framework\Controller\Web\HomeController;
use Donchev\Framework\Controller\Web\RouteController;
use Donchev\Framework\Controller\Web\SecurityController;

return [
    ['GET', '/', [HomeController::class, 'index']],
    ['GET', '/route/{id:\d+}', [RouteController::class, 'route']],
    ['GET', '/login', [SecurityController::class, 'login']],
    ['POST', '/login', [SecurityController::class, 'doLogin']],
    ['GET', '/logout', [SecurityController::class, 'logout']],
    ['GET', '/profile', [SecurityController::class, 'profile']],
    ['GET', '/password', [SecurityController::class, 'password']],
    ['POST', '/password', [SecurityController::class, 'passwordUpdate']],
];
