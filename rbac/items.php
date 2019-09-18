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
];
