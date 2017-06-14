<?php

return [
    'uploadDir' => implode(DIRECTORY_SEPARATOR, ['client', 'default']),
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
            'maxFiles'           => 5,
            'icon'               => 'picture-o',
        ],

        'video' => [
            'status'             => true,
            'acceptedExtensions' => ['mp4', 'avi', 'mkv'],
            'acceptedFiles'      => ['video/mp4', 'video/avi', 'video/x-matroska'],
            'maxFileSize'        => 100,
            'maxFiles'           => 5,
            'icon'               => 'file-video-o',
        ],

        'audio' => [
            'status'             => true,
            'acceptedExtensions' => ['mp3', 'wave', 'wma', 'mpga'],
            'acceptedFiles'      => [
                'audio/mpeg',
                'audio/x-mpeg',
                'audio/mp3',
                'audio/x-mp3',
//                'audio/mpeg3',
//                'audio/x-mpeg3',
                'audio/wav',
                'audio/x-wav',
//                'audio/wave',
                'audio/x-ms-wma'
            ],
            'maxFileSize'        => 10,
            'maxFiles'           => 5,
            'icon'               => 'file-audio-o',
        ],

        'text' => [
            'status'             => true,
            'acceptedExtensions' => ['pdf', 'doc', 'docs'],
            'acceptedFiles'      => [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ],
            'maxFileSize'        => 100,
            'maxFiles'           => 5,
            'icon'               => 'file-text-o',
        ],
    ]
];