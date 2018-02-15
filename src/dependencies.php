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
    return new \Emotion\Service\FormValidator($c->get('settings'));
};

$container['message_factory'] = function () {
    return new \Emotion\Service\Message\Factory\MessageFactory();
};

$container['user_factory'] = function () {
    return new \Emotion\Service\User\Factory\UserFactory();
};

$container['message_functions'] = function ($c) {
    $messageGateway = $c->get('message_gateway');
    $userGateway = $c->get('user_gateway');
    $messageFactory = $c->get('message_factory');
    $userFactory = $c->get('user_factory');
    return new \Emotion\Service\Message\MessageFunctions(
        $messageGateway,
        $userGateway,
        $messageFactory,
        $userFactory
    );
};

//CONTROLLERS
$container['index_controller'] = function ($c) {
    $view = $c->get('renderer');
    $formValidator = $c->get('form_validator');
    $userGateway = $c->get('user_gateway');
    $messageGateway = $c->get('message_gateway');
    $messageFunctionsService = $c->get('message_functions');
    return new \Emotion\Controller\Index(
        $view,
        $formValidator,
        $userGateway,
        $messageGateway,
        $messageFunctionsService,
        $c->get('settings')
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
    return new \Emotion\Gateway\Message(
        $table,
        $c->get('settings')
    );
};

