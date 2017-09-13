<?php

return [
    'uploadDir' => implode(DIRECTORY_SEPARATOR, ['client', 'angels']),
    'fileTypes'  => [
        /**
         * "status"             => if true => current file type of file will be uploading
         * "acceptedExtensions" => array of accepted extensions for current file type of file
         * "maxFileSize"        => max size of file that can be accepted (in MB)
         * "maxFiles"           => max number of files that could be uploaded in current file type
         * "icon"               => icon to show
         */

        'image' => [
            'status'             => true,
            'acceptedExtensions' => ['jpg', 'jpeg', 'png', 'gif'],
            'acceptedFiles'      => [
                'image/pjpeg',
                'image/jpeg',
                'image/png',
                'image/gif'
            ],
            'maxFileSize'        => 5,
            'maxFiles'           => 1,
            'icon'               => 'picture-o',
        ],

        'video' => [
            'status'             => false,
        ],

        'audio' => [
            'status'             => false,
        ],

        'text' => [
            'status'             => false,
        ],

        'compressed' => [
            'status'             => false,
        ],
    ]
];