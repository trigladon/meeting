<?php
defined('DEFAULT_LANGUAGE') || define('DEFAULT_LANGUAGE', 'en');
return array(

    'projectData' => [
        'siteName' => 'Meeting',
        'defaultLanguage' => DEFAULT_LANGUAGE,
        'defaultLanguageId' => 2,
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
                                'icon'  => 'icon-user-follow',
                                'action' => 'add',
                                'visible-in-menu' => false,
                            ],
                            [
                                'label' => 'Edit user',
                                'route' => 'admin-user',
                                'icon'  => 'icon-user',
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
                'route' => 'admin-advertising-place',
                'icon' => 'icon-fire',
                'pages' => [
                    [
                        'label' => 'Place',
                        'route' => 'admin-advertising-place',
                        'icon'  => 'icon-energy',
                        'pages' => [
                            [
                                'label' => 'Add place',
                                'route' => 'admin-advertising-place',
                                'icon'  => 'icon-energy',
                                'action' => 'add-place',
                                'visible-in-menu' => false,
                            ],
                            [
                                'label' => 'Edit place',
                                'route' => 'admin-advertising-place',
                                'icon'  => 'icon-energy',
                                'action' => 'edit-place',
                                'visible-in-menu' => false,
                            ]
                        ]
                    ],
                    [
                        'label' => 'Advertising',
                        'route' => 'admin-advertising',
                        'icon'  => 'icon-fire',
                        'action'=> 'all',
                        'pages' => [
                            [
                                'label' => 'Add advertising',
                                'route' => 'admin-advertising',
                                'icon'  => 'icon-fire',
                                'action' => 'add',
                                'visible-in-menu' => false,
                            ],
                            [
                                'label' => 'Edit advertising',
                                'route' => 'admin-advertising',
                                'icon'  => 'icon-fire',
                                'action' => 'edit',
                                'visible-in-menu' => false,
                            ]
                        ]
                    ]
                ]
            ],
            [
                'label' => 'Pages',
                'route' => 'admin-page',
                'icon' => 'icon-docs',
                'pages' => [
                    [
                        'label' => 'Add page',
                        'route' => 'admin-page',
                        'icon'  => 'icon-docs',
                        'action' => 'add',
                        'visible-in-menu' => false,
                    ],
                    [
                        'label' => 'Edit page',
                        'route' => 'admin-page',
                        'icon'  => 'icon-docs',
                        'action' => 'edit',
                        'visible-in-menu' => false,
                    ]
                ]
            ],
            [
                'label' => 'News',
                'route' => 'admin-category',
                'icon' => 'icon-book-open',
                'pages' => [
                    [
                        'label' => 'Categories',
                        'route' => 'admin-category',
                        'icon'  => 'icon-layers',
                        'pages' => [
                            [
                                'label' => 'Add category',
                                'route' => 'admin-category',
                                'icon'  => 'icon-note',
                                'action' => 'add-category',
                                'visible-in-menu' => false,
                            ],
                            [
                                'label' => 'Edit category',
                                'route' => 'admin-category',
                                'icon'  => 'icon-note',
                                'action' => 'edit-category',
                                'visible-in-menu' => false,
                            ]
                        ]
                    ],
                    [
                        'label' => 'News',
                        'route' => 'admin-news',
                        'icon'  => 'icon-doc',
                        'action'=> 'all',
                        'pages' => [
                            [
                                'label' => 'Add news',
                                'route' => 'admin-news',
                                'icon'  => 'icon-note',
                                'action' => 'add',
                                'visible-in-menu' => false,
                            ],
                            [
                                'label' => 'Edit news',
                                'route' => 'admin-news',
                                'icon'  => 'icon-note',
                                'action' => 'edit',
                                'visible-in-menu' => false,
                            ]
                        ]
                    ]
                ]
            ],
            [
                'label' => 'Countries',
                'route' => 'admin-country',
                'icon' => 'icon-globe',
                'pages' => [
                    [
                        'label' => 'Country',
                        'route' => 'admin-country',
                        'icon'  => 'icon-globe-alt',
                        'action' => 'all',
                        'pages' => [
                            [
                                'label' => 'Add country',
                                'route' => 'admin-country',
                                'icon'  => 'icon-globe-alt',
                                'action' => 'add',
                                'visible-in-menu' => false,
                            ],
                            [
                                'label' => 'Edit country',
                                'route' => 'admin-country',
                                'icon'  => 'icon-globe-alt',
                                'action' => 'edit',
                                'visible-in-menu' => false,
                            ]
                        ]
                    ],
                    [
                        'label' => 'City',
                        'route' => 'admin-city',
                        'icon'  => 'icon-map',
                        'action'=> 'all-cities',
                        'pages' => [
                            [
                                'label' => 'Add City',
                                'route' => 'admin-city',
                                'icon'  => 'icon-map',
                                'action' => 'add-city',
                                'visible-in-menu' => false,
                            ],
                            [
                                'label' => 'Edit city',
                                'route' => 'admin-city',
                                'icon'  => 'icon-map',
                                'action' => 'edit-city',
                                'visible-in-menu' => false,
                            ]
                        ]
                    ]
                ]
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
