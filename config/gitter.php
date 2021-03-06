<?php
use App\Gitter\Middleware\Storage;

return [
    'token' => env('GITTER_TOKEN', null),

    'rooms' => [
        '5602e05e0fc9f982beb19cdc', // Debug (Karma bot chat)
        '52f9b90e5e986b0712ef6b9d', // Laravel Chat
        '54053e51163965c9bc201c26', // Laravel Site
        '560281040fc9f982beb1908a', // Laravel Gitter Bot
        '555086c915522ed4b3e03631', // Yii Public
        '550177ff15522ed4b3dd296e', // Jphp
    ],

    'middlewares' => [
        App\Gitter\Middleware\LoggerMiddleware::class => Storage::PRIORITY_MAXIMAL,
        App\Gitter\Middleware\DbSyncMiddleware::class => Storage::PRIORITY_MAXIMAL,

        App\Gitter\Middleware\KarmaCounterMiddleware::class => Storage::PRIORITY_DEFAULT,
    ],
];
