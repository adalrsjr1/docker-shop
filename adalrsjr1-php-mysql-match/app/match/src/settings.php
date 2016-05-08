<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'match-service',
            'path' => __DIR__ . '/../logs/app.log',
            //'path' => '/usr/local/home/docker-imgs/alpine-php-mysql-shop/app-log/app.log',
        ],
    
    ],
];
