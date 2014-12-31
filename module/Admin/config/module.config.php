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
        ),
    ),

    'router' => array(
        'routes' => array(
            'admin-home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/adminomaniya',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Admin',
                        'action'     => 'dashboard',
                    ),
                ),
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
            'admin-user' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/adminomaniya/user[/:action[/:id]]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\User',
                        'action' => 'all'
                    )
                )
            ),
            'admin-patient' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/adminomaniya/patient[/:action[/:id]]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Patient',
                        'action' => 'all'
                    )
                )
            ),
            'admin-asset' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/adminomaniya/asset[/:action[/:id]]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Asset',
                        'action' => 'all'
                    )
                )
            ),

            'admin-page' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/adminomaniya/page[/:action[/:id]]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Page',
                        'action' => 'all'
                    )
                )
            ),

            'admin-language' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/adminomaniya/language[/:action[/:id]]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Language',
                        'action' => 'all'
                    )
                )
            ),

            'admin-country' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/adminomaniya/country[/:action[/:id]]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Country',
                        'action' => 'all'
                    )
                )
            ),

            'admin-city' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/adminomaniya/city[/:action[/:id]]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Country',
                        'action' => 'all-cities'
                    )
                )
            ),

            'admin-news' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/adminomaniya/news[/:action[/:id]]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\News',
                        'action' => 'all'
                    )
                )
            ),

            'admin-category' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/adminomaniya/category[/:action[/:id]]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\News',
                        'action' => 'all-category'
                    )
                )
            ),

            'admin-advertising' => [
                'type' => 'Segment',
                'options' => array(
                    'route' => '/adminomaniya/advertising[/:action[/:id]]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Advertising',
                        'action' => 'all'
                    )
                )
            ],

            'admin-advertising-place' => [
                'type' => 'Segment',
                'options' => array(
                    'route' => '/adminomaniya/advertising-place[/:action[/:id]]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Advertising',
                        'action' => 'all-places'
                    )
                )
            ],

            'admin-feedback' => [
                'type' => 'Segment',
                'options' => array(
                    'route' => '/adminomaniya/feedback[/:action[/:id]]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Feedback',
                        'action' => 'all'
                    )
                )
            ],
            'admin-feedback-answer' => [
                'type' => 'Segment',
                'options' => array(
                    'route' => '/adminomaniya/feedback/read/:id/answer/:idAnswer',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                        'idAnswer'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Feedback',
                        'action' => 'answer'
                    )
                )
            ]

        ),
    ),

    'view_manager' => array(
        'template_map' => array(
            'layout/admin'           => __DIR__ . '/../view/layout/admin.phtml',
            'layout/admin-login'     => __DIR__ . '/../view/layout/admin-login.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
	        'ViewJsonStrategy',
        ),
    ),
];
