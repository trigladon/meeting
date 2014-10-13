<?php

return array(

    'email' =>array(
        'transport' => array(
            'name' => 'localhost',
            'host' => '127.0.0.1',
            'port' => 25,
//            'name'              => 'localhost',
//            'host'              => '127.0.0.1',
//            'port'              => 25,//465,
//            'connection_class'  => 'login',
//            'connection_config' => array(
//                'username'      => 'meeting',
//                'password'      => '123123',
//                'ssl'           => 'tls',
//            ),
        ),
        'options' => array(
            'layout' => 'layout/email',
            'charset' => 'UTF-8',
        )
    ),

    'navigation' => [
        'admin' =>  [
            [
                'label' => 'Dashboard',
                'route' => 'admin-home',
                'icon'  => 'icon-home',
            ],
            [
                'label' => 'User',
                'route' => 'admin-login',
                'icon'  => 'icon-user',
                'pages' => [
                     [
                        'label' => 'Users login',
                        'route' => 'admin-login',
                        'icon'  => 'fa-user',
                    ],
                    [
                        'label' => 'Users logout',
                        'route' => 'admin-logout',
                        'icon'  => 'fa-user',
                    ]
                ]
            ]
        ],
    ],

    'projectData' => [
        'siteName' => 'Meeting',
        'defaultLanguage' => 'en',
        'defaultLanguageLocale' => 'en_EN',

        'emailSend' => 'help@meeting.com',
        'emailSendName' => 'Meeting.com',
    ]
);
