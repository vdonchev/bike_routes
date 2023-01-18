<?php

use Donchev\Framework\Controller\Web\HomeController;
use Donchev\Framework\Controller\Web\RouteController;

return [
    ['GET', '/', [HomeController::class, 'index']],
    ['GET', '/route/{id:\d+}', [RouteController::class, 'route']],
];
