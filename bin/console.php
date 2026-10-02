#!/usr/bin/env php
<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use Donchev\Framework\Command\CacheClearCommand;
use Symfony\Component\Console\Application;

$application = new Application();

$application->addCommand(new CacheClearCommand());

try {
    $application->run();
} catch (Exception $e) {
}
