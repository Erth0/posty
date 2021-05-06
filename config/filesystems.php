<?php

return [
    'default' => 'computer',
    'disks' => [
        'computer' => [
            'driver' => 'local',
            'root' => $_SERVER['HOME'] ?? $_SERVER['USERPROFILE'],
        ],
    ],
];
