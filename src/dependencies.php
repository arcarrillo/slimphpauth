<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

$container['em'] = function ($c) {
    $settings = $c->get('doctrine');
    
    $config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(
        $settings['meta']['entity_path'],
        $settings['meta']['auto_generate_proxies'],
        $settings['meta']['proxy_dir'],
        $settings['meta']['cache'],
        false
    );
    return \Doctrine\ORM\EntityManager::create($settings['connection'], $config);
};

$container['GoogleServices'] = function ($c) {
    return new App\Services\GoogleServices();
};
$container['JWTService'] = function ($c) {
    return new App\Services\JWTService();
};

$container['SessionService'] = function ($c) {
    return new App\Services\SessionService($c->get('em'));
};

$container['UserService'] = function ($c) {
    return new App\Services\UserService($c->get('em'));
};


$container['App\Controllers\PagesController'] = function ($c) {
    return new App\Controllers\PagesController($c->get('em'),
        $c->get('renderer'), $c->get('GoogleServices'),
        $c->get("UserService"), $c->get("SessionService"),
        $c->get("JWTService"));
};