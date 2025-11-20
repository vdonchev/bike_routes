<?php

namespace Deployer;

require 'recipe/common.php';

set('application', 'krisi.bike');

set('repository', 'git@github.com:vdonchev/bike_routes.git');

set('writable_dirs', []);
set('allow_anonymous_stats', false);

set(
    'composer_options',
    'install  --verbose --prefer-dist --optimize-autoloader --no-progress --no-interaction --no-scripts'
);

set(
    'console_options',
    function () {
        return '--no-interaction';
    }
);

set('keep_releases', 20);

host('217.182.196.155')
    ->stage('cloud')
    ->user('ubuntu')
    ->set('branch', 'master')
    ->set(
        'shared_files',
        [
            'var/log/application.log',
            'public/7fbd95b080fce50b0b0332c4da6cb39b.log',
            'config/settings.local.yaml',
        ]
    )
    ->set(
        'shared_dirs',
        [
            'public/map',
            'public/gpx',
            'public/media',
        ]
    )
    ->port(22)
    ->set('deploy_path', '/var/www/krisi.bike');

task(
    'deploy:set_permissions',
    function () {
        run('sudo chown -R www-data:$USER /var/www/krisi.bike/current/var/log');
        run('sudo chown -R www-data:$USER /var/www/krisi.bike/current/public');
    }
);

task('reload:php-fpm', function () {
    run('sudo /usr/sbin/service php7.4-fpm reload');
});

desc('Deploy your project');
task(
    'deploy',
    [
        'deploy:info',
        'deploy:prepare',
        'deploy:lock',
        'deploy:release',
        'deploy:update_code',
        'deploy:shared',
        'deploy:writable',
        'deploy:vendors',
        'deploy:clear_paths',
        'deploy:symlink',
        'deploy:set_permissions',
        'deploy:unlock',
        'reload:php-fpm',
        'cleanup',
        'success',
    ]
);

after('deploy:failed', 'deploy:unlock');
