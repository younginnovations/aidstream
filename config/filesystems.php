<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. A "local" driver, as well as a variety of cloud
    | based drivers are available for your choosing. Just store away!
    |
    | Supported: "local", "s3", "rackspace"
    |
    */

    'default' => 'local',
    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => 's3',
    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    */

    'disks'                      => [

        'local'     => [
            'driver' => 'local',
            'root'   => public_path('/files'),
        ],
        's3'        => [
            'driver' => 's3',
            'key'    => 'your-key',
            'secret' => 'your-secret',
            'region' => 'your-region',
            'bucket' => 'your-bucket',
        ],
        'rackspace' => [
            'driver'    => 'rackspace',
            'username'  => 'your-username',
            'key'       => 'your-key',
            'container' => 'your-container',
            'endpoint'  => 'https://identity.api.rackspacecloud.com/v2.0/',
            'region'    => 'IAD',
            'url_type'  => 'publicURL'
        ],
        'dropbox'   => [
            'driver'      => 'dropbox',
            'accessToken' => env('DROPBOX_ACCESS_TOKEN'),
            'appSecret'   => env('DROPBOX_APP_SECRET'),
        ]

    ],
    /**
     * Path where all the generated xml files are stored in Aidstream.
     */
    'xml'                        => '/xml/',
    /**
     * Path where all the uploaded documents are stored in Aidstream.
     */
    'documents'                  => '/documents/',
    /**
     * Api URL for the IATI Registry.
     */
    'iati_registry_api_base_url' => env('REGISTRY_URL', 'test'),
    'iati_registry_dummy_url'    => ''

];
