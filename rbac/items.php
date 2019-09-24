<?php
return [
    'root' => [
        'type' => 1,
        'description' => 'root user',
        'children' => [
            '/*',
        ],
    ],
    '/*' => [
        'type' => 2,
    ],
    '/admin/*' => [
        'type' => 2,
    ],
    '/admin/assignment/index' => [
        'type' => 2,
    ],
    '/site/change-password' => [
        'type' => 2,
    ],
    'admin' => [
        'type' => 1,
        'children' => [
            '/site/signup',
            '/site/index',
            '/app/*',
        ],
    ],
    'podved' => [
        'type' => 1,
        'children' => [
            '/app/students/index',
            '/app/students/view',
            '/app/students/update',
            '/site/index',
            '/app/students/download',
        ],
    ],
    '/app/students/*' => [
        'type' => 2,
    ],
    '/app/students/delete' => [
        'type' => 2,
    ],
    '/app/students/create' => [
        'type' => 2,
    ],
    '/app/students/update' => [
        'type' => 2,
    ],
    '/app/students/view' => [
        'type' => 2,
    ],
    '/app/students/index' => [
        'type' => 2,
    ],
    '/site/signup' => [
        'type' => 2,
    ],
    '/site/index' => [
        'type' => 2,
    ],
    '/app' => [
        'type' => 2,
    ],
    '/app/*' => [
        'type' => 2,
    ],
    '/beautyfiles/*' => [
        'type' => 2,
    ],
    '/beautyfiles/default/index' => [
        'type' => 2,
    ],
    '/app/students/download' => [
        'type' => 2,
    ],
];
