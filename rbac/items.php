<?php
return [
    'accessTable' => [
        'type' => 2,
        'description' => 'Access Table',
    ],
    'viewTable' => [
        'type' => 2,
        'description' => 'View Table',
    ],
    'editTable' => [
        'type' => 2,
        'description' => 'Edit Table',
    ],
    'user' => [
        'type' => 1,
        'description' => 'User',
        'children' => [
            'accessTable',
            'viewTable',
        ],
    ],
    'admin' => [
        'type' => 1,
        'description' => 'Admin',
        'children' => [
            'accessTable',
            'viewTable',
            'editTable',
        ],
    ],
];
