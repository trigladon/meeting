<?php

return array(

    'projectData' => [
        'siteName' => 'Meeting',
        'defaultLanguage' => 'en',
        'defaultLanguageId' => 1,
        'defaultLanguageLocale' => 'en_EN',

        'emailSend' => 'help@meeting.com',
        'emailSendName' => 'Meeting.com',

        'options' => [
            'dateTimeFormat' => 'Y-m-d h:i:s',
            'dateFormat' => 'Y-m-d',
            
            'dateFormatJs' => 'yyyy-mm-dd',
        ],

        'files' => [
            'size' => 2048000,
        ]

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
        'options' => [
            'remember_me_seconds' => 2419200,
            'use_cookies'       => true,
            'cookie_httponly'   => true,
            'cookie_lifetime'   => 2419200,
            'gc_maxlifetime'    => 2419200,
        ]
    ),

    'navigation' => [
        'admin' =>  [
            [
                'label' => 'Dashboard',
                'route' => 'admin-home',
                'icon'  => 'icon-home',
            ],
            [
                'label' => 'Accounts',
                'route' => 'admin-user',
                'icon'  => 'icon-users',
                'pages' => [
                     [
                        'label' => 'All users',
                        'route' => 'admin-user',
                        'icon'  => 'fa-users',
                        'action' => 'all',
                        'pages' => [
                            [
                                'label' => 'Add user',
                                'route' => 'admin-user',
                                'icon'  => 'fa-user',
                                'action' => 'add',
                                'visible-in-menu' => false,
                            ],
                            [
                                'label' => 'Edit user',
                                'route' => 'admin-user',
                                'icon'  => 'fa-user',
                                'action' => 'edit',
                                'visible-in-menu' => false,
                            ]
                        ]
                    ],
                    [
                        'label' => 'Patients',
                        'route' => 'admin-patient',
                        'icon'  => 'fa-user-md',
                        'action'=> 'all',
                        'pages' => [
                            [
                                'label' => 'Add patient',
                                'route' => 'admin-patient',
                                'icon'  => 'fa-user-md',
                                'action' => 'add',
                                'visible-in-menu' => false,
                            ],
                            [
                                'label' => 'Edit patient',
                                'route' => 'admin-patient',
                                'icon'  => 'fa-user-md',
                                'action' => 'edit',
                                'visible-in-menu' => false,
                            ]
                        ]
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
                'route' => 'admin-page',
                'icon' => 'icon-docs',
            ],
            [
                'label' => 'Assets',
                'route' => 'admin-asset',
                'icon' => 'icon-picture',
                'pages' => [
                    [
                        'label' => 'Add asset',
                        'route' => 'admin-asset',
                        'icon'  => 'icon-picture',
                        'action' => 'add',
                        'visible-in-menu' => false,
                    ],
                    [
                        'label' => 'Edit asset',
                        'route' => 'admin-asset',
                        'icon'  => 'icon-picture',
                        'action' => 'edit',
                        'visible-in-menu' => false,
                    ]
                ]
            ],
            [
                'label' => 'Languages',
                'route' => 'admin-language',
                'icon' => 'icon-flag',
                'pages' => [
                    [
                        'label' => 'Add language',
                        'route' => 'admin-language',
                        'icon'  => 'icon-flag',
                        'action' => 'add',
                        'visible-in-menu' => false,
                    ],
                    [
                        'label' => 'Edit language',
                        'route' => 'admin-language',
                        'icon'  => 'icon-flag',
                        'action' => 'edit',
                        'visible-in-menu' => false,
                    ]
                ]
            ],
        ],
    ],


);
