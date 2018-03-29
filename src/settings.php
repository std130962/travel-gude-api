<?php
/**
 * The settings file
 */


return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Monolog settings
        'logger' => [
            'name' => 'travel-guide-app',
            'path' =>  __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        // DB settings
        'db' => [
            'host' => 'localhost',
            'dbname' => 'travel_guide',
            'user' => 'root',
            'pass' => ''
        ],

        // Images folders
        'imagesUrl' => [
            'images' => '/images/',
            'thumbnails' => '/images/thumbs/'
        ]
    ],
];