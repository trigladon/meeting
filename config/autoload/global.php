<?php

return array(

    'projectData' => [
        'siteName' => 'Meeting',
        'defaultLanguage' => 'en',
        'defaultLanguageLocale' => 'en_EN',

        'emailSend' => 'help@meeting.com',
        'emailSendName' => 'Meeting.com',
    ],

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

    'session' => array(
        'remember_me_seconds' => 2419200,
        'use_cookies'       => true,
        'cookie_httponly'   => true,
        'cookie_lifetime'   => 2419200,
        'gc_maxlifetime'    => 2419200,
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
                'route' => 'admin-user',
                'icon'  => 'icon-users',
                'action' => 'all',
                'pages' => [
                     [
                        'label' => 'All users',
                        'route' => 'admin-user',
                        'icon'  => 'fa-users',
                        'action' => 'all',
                    ],
                    [
                        'label' => 'Patients',
                        'route' => 'admin-user',
                        'icon'  => 'fa-user-md',
                        'action'=> 'patients'
                    ]
                ]
            ],
            [
                'label' => 'Meetings',
                'route' => 'admin-login',
                'icon'  => 'icon-bubbles',
                'pages' => [
                    [
                        'label' => 'All meetings',
                        'route' => 'admin-login',
                        'icon' => 'fa-comments-o'
                    ],
                    [
                        'label' => 'Active',
                        'route' => 'admin-login',
                        'icon' => 'fa-comments'
                    ],

                ]
            ],
            [
                'label' => 'Payments',
                'route' => 'admin-login',
                'icon' => 'icon-wallet',
            ],
            [
                'label' => 'Feedback',
                'route' => 'admin-login',
                'icon' => 'icon-envelope-letter'
            ],
            [
                'label' => 'Advertising',
                'route' => 'admin-login',
                'icon' => 'icon-fire',
            ],
            [
                'label' => 'Pages',
                'route' => 'admin-login',
                'icon' => 'icon-docs',
            ],

        ],
    ],


);
