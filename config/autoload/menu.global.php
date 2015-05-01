<?php

return [
    'navigation' => [
        'admin' =>  [
            [
                'label' => 'Dashboard',
                'route' => 'admin',
                'icon'  => 'icon-home',
            ],
            [
                'label' => 'Accounts',
                'route' => 'admin/default',
                "controller" => "user",
                'icon'  => 'icon-users',
                'pages' => [
                    [
                        'label' => 'All users',
                        'route' => 'admin/default',
                        "controller" => "user",
                        'icon'  => 'fa-users',
                        'action' => 'all',
                        'pages' => [
                            [
                                'label' => 'Add user',
                                'route' => 'admin/default',
                                "controller" => "user",
                                'icon'  => 'icon-user-follow',
                                'action' => 'add',
                                'visible-in-menu' => false,
                            ],
                            [
                                'label' => 'Edit user',
                                'route' => 'admin/default',
                                "controller" => "user",
                                'icon'  => 'icon-user',
                                'action' => 'edit',
                                'visible-in-menu' => false,
                            ]
                        ]
                    ],
                    [
                        'label' => 'Patients',
                        'route' => 'admin/default',
                        'controller' => 'patient',
                        'icon'  => 'fa-user-md',
                        'action'=> 'all',
                        'pages' => [
                            [
                                'label' => 'Add patient',
                                'route' => 'admin/default',
                                'controller' => 'patient',
                                'icon'  => 'fa-user-md',
                                'action' => 'add',
                                'visible-in-menu' => false,
                            ],
                            [
                                'label' => 'Edit patient',
                                'route' => 'admin/default',
                                'controller' => 'patient',
                                'icon'  => 'fa-user-md',
                                'action' => 'edit',
                                'visible-in-menu' => false,
                            ]
                        ]
                    ]
                ]
            ],
            [
                'label' => 'Auctions',
                'route' => 'admin/default',
                'controller' => 'auction',
                'icon'  => 'icon-bubbles',
                'pages' => [
                    [
                        'label' => 'Add auction',
                        'route' => 'admin/default',
                        'controller' => 'auction',
                        'icon'  => 'fa-comments-o',
                        'action' => 'add',
                        'visible-in-menu' => false,
                    ],
                    [
                        'label' => 'Edit auction',
                        'route' => 'admin/default',
                        'controller' => 'auction',
                        'icon'  => 'fa-comments-o',
                        'action' => 'edit',
                        'visible-in-menu' => false,
                        'pages' => [
                            [
                                'label' => 'Auction comments',
                                'route' => 'admin/default',
                                'controller' => 'auction',
                                'action' => 'comment',
                                'icon' => 'fa-comments',
                                'visible-in-menu' => false,
                            ],
                            [
                                'label' => 'Auction rates',
                                'route' => 'admin/default',
                                'controller' => 'auction',
                                'action' => 'rate',
                                'icon' => 'fa-comments',
                                'visible-in-menu' => false,
                            ],
                        ]
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
                'route' => 'admin/default',
                'controller' => 'feedback',
                'icon' => 'icon-envelope-letter',
                'pages' => [
                    [
                        'label' => 'Read',
                        'route' => 'admin/default',
                        'controller' => 'feedback',
                        'icon'  => 'icon-envelope-letter',
                        'action' => 'read',
                        'visible-in-menu' => false,
//                        'pages' => [
//                            [
//                                'label' => 'View answer',
//                                'route' => 'admin/default-answer',
//                                'icon'  => 'icon-envelope-letter',
//                                'visible-in-menu' => false,
//                            ],
//                        ],
                    ],
//                    [
//                        'label' => 'View answer',
//                        'route' => 'admin-feedback-answer',
//                        'icon'  => 'icon-envelope-letter',
//                        'visible-in-menu' => false,
//                    ],
                ]

            ],
            [
                'label' => 'Advertising',
                'route' => 'admin/default',
                "controller" => "advertising",
                "action" => "all",
                'icon' => 'icon-fire',
                'pages' => [
                    [
                        'label' => 'Place',
                        'route' => 'admin/default',
                        "controller" => "advertising",
                        "action" => "all-places",
                        'icon'  => 'icon-energy',
                        'pages' => [
                            [
                                'label' => 'Add place',
                                'route' => 'admin/default',
                                'icon'  => 'icon-energy',
                                "controller" => "advertising",
                                'action' => 'add-place',
                                'visible-in-menu' => false,
                            ],
                            [
                                'label' => 'Edit place',
                                'route' => 'admin/default',
                                'icon'  => 'icon-energy',
                                "controller" => "advertising",
                                'action' => 'edit-place',
                                'visible-in-menu' => false,
                            ]
                        ]
                    ],
                    [
                        'label' => 'Advertising',
                        'route' => 'admin/default',
                        "controller" => "advertising",
                        'icon'  => 'icon-fire',
                        'action'=> 'all',
                        'pages' => [
                            [
                                'label' => 'Add advertising',
                                'route' => 'admin/default',
                                "controller" => "advertising",
                                'icon'  => 'icon-fire',
                                'action' => 'add',
                                'visible-in-menu' => false,
                            ],
                            [
                                'label' => 'Edit advertising',
                                'route' => 'admin/default',
                                "controller" => "advertising",
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
                'route' => 'admin/default',
                'controller' => 'page',
                'icon' => 'icon-docs',
                'pages' => [
                    [
                        'label' => 'Add page',
                        'route' => 'admin/default',
                        'controller' => 'page',
                        'icon'  => 'icon-docs',
                        'action' => 'add',
                        'visible-in-menu' => false,
                    ],
                    [
                        'label' => 'Edit page',
                        'route' => 'admin/default',
                        'controller' => 'page',
                        'icon'  => 'icon-docs',
                        'action' => 'edit',
                        'visible-in-menu' => false,
                    ]
                ]
            ],
            [
                'label' => 'News',
                'route' => 'admin/default',
                'controller' => 'news',
                'icon' => 'icon-book-open',
                'pages' => [
                    [
                        'label' => 'Categories',
                        'route' => 'admin/default',
                        'controller' => 'news',
                        'action' => 'all-category',
                        'icon'  => 'icon-layers',
                        'pages' => [
                            [
                                'label' => 'Add category',
                                'route' => 'admin/default',
                                'controller' => 'news',
                                'icon'  => 'icon-note',
                                'action' => 'add-category',
                                'visible-in-menu' => false,
                            ],
                            [
                                'label' => 'Edit category',
                                'route' => 'admin/default',
                                'controller' => 'news',
                                'icon'  => 'icon-note',
                                'action' => 'edit-category',
                                'visible-in-menu' => false,
                            ]
                        ]
                    ],
                    [
                        'label' => 'News',
                        'route' => 'admin/default',
                        'controller' => 'news',
                        'icon'  => 'icon-doc',
                        'action'=> 'all',
                        'pages' => [
                            [
                                'label' => 'Add news',
                                'route' => 'admin/default',
                                'controller' => 'news',
                                'icon'  => 'icon-note',
                                'action' => 'add',
                                'visible-in-menu' => false,
                            ],
                            [
                                'label' => 'Edit news',
                                'route' => 'admin/default',
                                'controller' => 'news',
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
                'route' => 'admin/default',
                'controller' => 'country',
                'icon' => 'icon-globe',
                'pages' => [
                    [
                        'label' => 'Country',
                        'route' => 'admin/default',
                        'controller' => 'country',
                        'icon'  => 'icon-globe-alt',
                        'action' => 'all',
                        'pages' => [
                            [
                                'label' => 'Add country',
                                'route' => 'admin/default',
                                'controller' => 'country',
                                'icon'  => 'icon-globe-alt',
                                'action' => 'add',
                                'visible-in-menu' => false,
                            ],
                            [
                                'label' => 'Edit country',
                                'route' => 'admin/default',
                                'controller' => 'country',
                                'icon'  => 'icon-globe-alt',
                                'action' => 'edit',
                                'visible-in-menu' => false,
                            ]
                        ]
                    ],
                    [
                        'label' => 'City',
                        'route' => 'admin/default',
                        'controller' => 'city',
                        'icon'  => 'icon-map',
                        'action'=> 'all-cities',
                        'pages' => [
                            [
                                'label' => 'Add City',
                                'route' => 'admin/default',
                                'controller' => 'city',
                                'icon'  => 'icon-map',
                                'action' => 'add-city',
                                'visible-in-menu' => false,
                            ],
                            [
                                'label' => 'Edit city',
                                'route' => 'admin/default',
                                'controller' => 'city',
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
                'route' => 'admin/default',
                'controller' => 'asset',
                'icon' => 'icon-picture',
                'pages' => [
                    [
                        'label' => 'Add asset',
                        'route' => 'admin/default',
                        'controller' => 'asset',
                        'icon'  => 'icon-picture',
                        'action' => 'add',
                        'visible-in-menu' => false,
                    ],
                    [
                        'label' => 'Edit asset',
                        'route' => 'admin/default',
                        'controller' => 'asset',
                        'icon'  => 'icon-picture',
                        'action' => 'edit',
                        'visible-in-menu' => false,
                    ]
                ]
            ],
            [
                'label' => 'Languages',
                'route' => 'admin/default',
                'controller' => 'language',
                'icon' => 'icon-flag',
                'pages' => [
                    [
                        'label' => 'Add language',
                        'route' => 'admin/default',
                        'controller' => 'language',
                        'icon'  => 'icon-flag',
                        'action' => 'add',
                        'visible-in-menu' => false,
                    ],
                    [
                        'label' => 'Edit language',
                        'route' => 'admin/default',
                        'controller' => 'language',
                        'icon'  => 'icon-flag',
                        'action' => 'edit',
                        'visible-in-menu' => false,
                    ]
                ]
            ],
        ],
    ],
];