<?php

use Donchev\Framework\Controller\Web\HomeController;
use Donchev\Framework\Controller\Web\RouteController;
use Donchev\Framework\Controller\Web\SecurityController;
use Donchev\Framework\Controller\Web\MediaController;

return [
    ['GET', '/', [HomeController::class, 'index']],
    ['GET', '/races', [HomeController::class, 'races']],

    ['GET', '/route/{id:\d+}', [RouteController::class, 'route']],
    ['GET', '/route/add', [MediaController::class, 'uploadRoute']],
    ['GET', '/gpx/download/{id:\d+}', [MediaController::class, 'downloadGpx']],
    ['POST', '/upload-image', [MediaController::class, 'uploadImage']],
    ['GET', '/media/delete/{mediaId:\d+}/{routeId:\d+}', [MediaController::class, 'deleteMedia']],

    ['GET', '/login', [SecurityController::class, 'login']],
    ['POST', '/login', [SecurityController::class, 'doLogin']],
    ['GET', '/logout', [SecurityController::class, 'logout']],
    ['GET', '/profile', [SecurityController::class, 'profile']],
    ['GET', '/password', [SecurityController::class, 'password']],
    ['POST', '/password', [SecurityController::class, 'passwordUpdate']],
];
