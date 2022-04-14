<?php

use App\Controller\HelloController;
use App\Controller\SecurityController;

return [
    '/' => [
        'class' => HelloController::class,
        'method' => 'index',
    ],
    '/login' => [
        'class' => SecurityController::class,
        'method' => 'login',
    ],
    '/logout' => [
        'class' => SecurityController::class,
        'method' => 'logout',
    ],
    '/register' => [
        'class' => SecurityController::class,
        'method' => 'register',
    ],
    '/me' => [
        'class' => SecurityController::class,
        'method' => 'me',
    ]
];
