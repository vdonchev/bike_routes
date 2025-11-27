<?php

namespace Deployer;

require 'recipe/common.php';
require 'recipe/composer.php';

// Project
set('application', 'krisi.bike');
set('repository', 'git@github.com:vdonchev/bike_routes.git');
set('branch', 'master');
set('keep_releases', 5);
set('allow_anonymous_stats', false);
set('writable_mode', 'chmod');

// Shared & writable
add('shared_files', [
    'var/log/application.log',
    'public/7fbd95b080fce50b0b0332c4da6cb39b.log',
    'config/settings.local.yaml',
]);
add('shared_dirs', [
    'public/map',
    'public/gpx',
    'public/media',
]);
add('writable_dirs', []);

// Host
host('production')
    ->setHostname('151.80.145.236')
    ->set('remote_user', 'ubuntu')
    ->set('forward_agent', true)
    ->set('deploy_path', '/var/www/krisi.bike');

// Custom tasks
desc('Reload PHP-FPM');
task('php-fpm:reload', function () {
    run('sudo systemctl reload php8.3-fpm.service');
});

desc('Set permissions');
task('deploy:set_permissions', function () {
    run('sudo chown -R www-data:$USER {{release_path}}/var/log');
    run('sudo chown -R www-data:$USER {{release_path}}/var/cache');
    run('sudo chown -R www-data:$USER {{release_path}}/public');
});

// Hooks
after('deploy:symlink', 'deploy:set_permissions');
after('deploy:set_permissions', 'php-fpm:reload');
after('deploy:failed', 'deploy:unlock');

// SSH
set('ssh_multiplexing', true);
set('ssh_options', ['StrictHostKeyChecking=no']);
