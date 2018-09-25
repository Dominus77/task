<?php
return [
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
            'viewTable',
        ],
    ],
    'admin' => [
        'type' => 1,
        'description' => 'Admin',
        'children' => [
            'viewTable',
            'editTable',
        ],
    ],
];
