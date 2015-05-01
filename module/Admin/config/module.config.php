<?php

return [
    'controllers' => array(
        'invokables' => array(
            'Admin\Controller\Auth'         => 'Admin\Controller\AuthController',
            'Admin\Controller\Admin'        => 'Admin\Controller\AdminController',
            'Admin\Controller\User'         => 'Admin\Controller\UserController',
            'Admin\Controller\Patient'      => 'Admin\Controller\PatientController',
            'Admin\Controller\Asset'        => 'Admin\Controller\AssetController',
            'Admin\Controller\Page'         => 'Admin\Controller\PageController',
            'Admin\Controller\Language'     => 'Admin\Controller\LanguageController',
            'Admin\Controller\Country'      => 'Admin\Controller\CountryController',
            'Admin\Controller\News'         => 'Admin\Controller\NewsController',
            'Admin\Controller\Advertising'  => 'Admin\Controller\AdvertisingController',
            'Admin\Controller\Feedback'     => 'Admin\Controller\FeedbackController',
            'Admin\Controller\Auction'      => 'Admin\Controller\AuctionController',
            'Admin\Controller\Comment'      => 'Admin\Controller\CommentController',

            'advertising'  => 'Admin\Controller\AdvertisingController',
            'auth'         => 'Admin\Controller\AuthController',
            'admin'        => 'Admin\Controller\AdminController',
            'user'         => 'Admin\Controller\UserController',
            'patient'      => 'Admin\Controller\PatientController',
            'asset'        => 'Admin\Controller\AssetController',
            'page'         => 'Admin\Controller\PageController',
            'language'     => 'Admin\Controller\LanguageController',
            'country'      => 'Admin\Controller\CountryController',
            'news'         => 'Admin\Controller\NewsController',
            'feedback'     => 'Admin\Controller\FeedbackController',
            'auction'      => 'Admin\Controller\AuctionController',
            'comment'      => 'Admin\Controller\CommentController',
        ),
    ),

    'router' => array(
        'routes' => array(

            "admin" => [
                'type' => 'Literal',
                'options' => array(
                    'route' => '/adminomaniya',
                    'defaults' => array(
                        'controller' => 'admin',
                        'action' => 'dashboard',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '[/:controller[/:action[/:id]]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]+',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]+',
                                'id'     => '[0-9]+',
                            ),
                            'defaults' => array(
                                "controller" => 'admin',
                                'action' => 'all',

                            ),
                        ),
                    ),
                    'default-answer' => [
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/:controller/read/:id/answer/:idAnswer',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]+',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]+',
                                'id'     => '[0-9]+',
                                'idAnswer'     => '[0-9]+',
                            ),
                            'defaults' => array(
                                "controller" => 'admin',
                                'action' => 'all',

                            ),
                        ),
                    ]
                ),
            ],

	        'admin-recovery-password' => array(
		        'type' => 'Literal',
		        'options' => array(
			        'route' => '/adminomaniya/recovery-password',
			        'defaults' => array(
						'controller' => 'Admin\Controller\Auth',
						'action' => 'recovery-password'
					)
		        )
	        ),

            'admin-login' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/adminomaniya/login',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Auth',
                        'action' => 'login',
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(

                    'admin_captcha_form' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/[:controller[/[:action[/]]]]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(),
                        ),
                    ),
                ),
            ),
            'admin-refresh-captcha'=> array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/adminomaniya/refresh-captcha',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Auth',
                        'action' => 'refresh-captcha'
                    )
                )
            ),
            'admin-logout' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/adminomaniya/logout',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Auth',
                        'action' => 'logout'
                    )
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'not_found_template' => 'error/404',
        'template_map' => require __DIR__  .'/template_map.php',
        'strategies' => array(
	        'ViewJsonStrategy',
        ),
    ),
];


