<?php

return [
    'sftp' => [
        'type' => 'Sftp',
        'host' => env('REMOTE_SERVER_BACKUP'),
        'username' => env('REMOTE_SERVER_BACKUP_USER'),
        'password' => env('REMOTE_SERVER_BACKUP_PASSWORD'),
        'port' => 2222,
        'timeout' => 10,
        'privateKey' => '',
        'root' => env('REMOTE_SERVER_BACKUP_PATH'),
    ]
];
