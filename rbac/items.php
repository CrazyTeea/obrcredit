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
            '/admin/*',
            '/site/change-password',
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
            '/app/students/approve',
            '/app/main/*',
            '/app/students/by-bank',
            '/app/students/all',
            '/app/students/delete-doc',
            '/app/students/ab',
            '/app/students/dp',
            '/app/students/history',
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
    '/app/students/approve' => [
        'type' => 2,
    ],
    '/app/main/*' => [
        'type' => 2,
    ],
    '/app/main/index' => [
        'type' => 2,
    ],
    '/app/main/month' => [
        'type' => 2,
    ],
    '/app/students/by-bank' => [
        'type' => 2,
    ],
    '/app/students/all' => [
        'type' => 2,
    ],
    '/app/students/turn-off' => [
        'type' => 2,
    ],
    '/app/students/delete-view' => [
        'type' => 2,
    ],
    '/app/students/delete-doc' => [
        'type' => 2,
    ],
    '/app/organizations/users' => [
        'type' => 2,
    ],
    '/app/organizations/off-orgs' => [
        'type' => 2,
    ],
    '/app/students-admin/*' => [
        'type' => 2,
    ],
    '/app/students-admin/index' => [
        'type' => 2,
    ],
    '/app/students/ab' => [
        'type' => 2,
    ],
    '/app/students/dp' => [
        'type' => 2,
    ],
    '/app/students/history' => [
        'type' => 2,
    ],
];
