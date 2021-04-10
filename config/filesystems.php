<?php

return [
    'default' => 'local',
    'disks' => [
        'local' => [
            'driver' => 'local',
            'root' => getcwd(),
        ],
        'computer' => [
            'driver' => 'local',
            'root' => '/',
        ],
    ],
];
