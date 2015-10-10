<?php

// All Deployer recipes are based on `recipe/common.php`.
require 'recipe/composer.php';

$config = parse_ini_file(__DIR__.'/deploy.ini');

env('timezone', 'Europe/Vienna');

// Specify the repository from which to download your project's code.
// The server needs to have git installed for this to work.
// If you're not using a forward agent, then the server has to be able to clone
// your project from this repository.
set('repository', $config['repo']);

set('keep_releases', 5);

server('prod', $config['host'], $config['port'])
    ->user($config['user'])
    ->password($config['password'])
    ->stage('production')
    ->env('deploy_path', $config['path'])
    ->env('env_vars', $config['env_vars']);

task('deploy:push', function () {
    runLocally("git push");
})->desc('Installing vendors');

task('deploy:vendors', function () {
    $envVars = env('env_vars') ? 'export ' . env('env_vars') . ' &&' : '';
    run("cd {{release_path}} && $envVars sculpin update --env prod");
})->desc('Installing vendors');

task('deploy:generate', function () {
    $envVars = env('env_vars') ? 'export ' . env('env_vars') . ' &&' : '';
    run("cd {{release_path}} && $envVars sculpin generate --env prod");
})->desc('Generating static HTML');

task('deploy', [
    'deploy:push',
    'deploy:prepare',
    'deploy:release',
    'deploy:update_code',
    'deploy:vendors',
    'deploy:shared',
    'deploy:generate',
    'deploy:symlink',
    'cleanup',
])->desc('Deploy your project');
