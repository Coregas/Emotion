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

//SERVICES

$container['form_validator'] = function ($c) {
    return new \Emotion\Service\FormValidator();
};

//CONTROLLERS
$container['index_controller'] = function ($c) {
    $view = $c->get('renderer');
    $formValidator = $c->get('form_validator');
    $userGateway = $c->get('user_gateway');
    $messageGateway = $c->get('message_gateway');
    return new \Emotion\Controller\Index(
        $view,
        $formValidator,
        $userGateway,
        $messageGateway
    );
};

//GATEWAYS
$container['db'] = function ($container) {
    $capsule = new \Illuminate\Database\Capsule\Manager;
    $capsule->addConnection($container['settings']['db']);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule;
};
$container['user_gateway'] = function ($c) {
    $table = $c->get('db')->table('user');
    return new \Emotion\Gateway\User($table);
};

$container['message_gateway'] = function ($c) {
    $table = $c->get('db')->table('message');
    return new \Emotion\Gateway\Message($table);
};

