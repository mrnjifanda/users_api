<?php
    use App\App;
    use App\Router;
    define('ROOT', '../'); # dirname(__DIR__).

    require ROOT . 'vendor/autoload.php';

    $isDev = true;
    $app = new App($isDev);
    $router = new Router(ROOT . 'app/', $app);
    
    $router
        ->post('user', 'user')
        ->get('users', 'users')
    ->run();