<?php
namespace Deployer;

require 'recipe/common.php';

// Configuration
set('application', 'Knowledge Learning');
set('repository', 'git@github.com:votre-repo/knowledge-learning.git');
set('git_tty', true);
set('shared_files', ['.env']);
set('shared_dirs', ['storage']);
set('writable_dirs', ['storage']);

// Serveurs
host('production')
    ->set('hostname', 'votre-serveur.com')
    ->set('remote_user', 'deploy')
    ->set('deploy_path', '/var/www/knowledge-learning');

// Tâches
task('deploy', [
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
    'deploy:unlock',
    'cleanup',
    'success'
]);

after('deploy:failed', 'deploy:unlock');
