<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header
        'determineRouteBeforeAppMiddleware' => false,

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        //db
    'db' => [
        'driver' => 'mysql',
        'host' => 'localhost',
        'database' => 'message_board',
        'username' => 'root',
        'password' => '123',
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix'    => '',
        ],

    'pagination' => [
            'perPage' => 5
        ],
    'form_field_rules' => [
        'birthdate_format'=> 'Y-m-d',
        'birthdate_format_example'=> 'YYYY-MM-DD',
        'name_max_length' => 70,
        'message_max_length' => 200
    ]
    ],
];
