<?php

use Donchev\Framework\Controller\Web\DownloadController;
use Donchev\Framework\Controller\Web\HomeController;
use Donchev\Framework\Controller\Web\RouteController;
use Donchev\Framework\Controller\Web\SecurityController;
use Donchev\Framework\Controller\Web\MediaController;

return [
    ['GET', '/', [HomeController::class, 'index']],
    ['GET', '/route/{id:\d+}', [RouteController::class, 'route']],
    ['GET', '/login', [SecurityController::class, 'login']],
    ['POST', '/login', [SecurityController::class, 'doLogin']],
    ['GET', '/logout', [SecurityController::class, 'logout']],
    ['GET', '/profile', [SecurityController::class, 'profile']],
    ['GET', '/password', [SecurityController::class, 'password']],
    ['POST', '/password', [SecurityController::class, 'passwordUpdate']],
    ['GET', '/download-gpx/{id:\d+}', [DownloadController::class, 'gpx']],
    ['POST', '/upload-image', [MediaController::class, 'uploadImage']],
];
