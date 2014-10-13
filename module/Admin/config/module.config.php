<?php

return [
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

	                'admin_captcha_form_generate' => array(
		                'type'    => 'segment',
		                'options' => array(
			                'route'    =>  '/[:controller[/captcha/[:id]]]',
			                'constraints' => array(
				                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
			                ),
			                'defaults' => array(
				                'controller' => 'Auth',
				                'action'     => 'generate-captcha',
			                ),
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
	        )
        ),
    ),

    'service_manager' => array(
        'factories' => array(
            'admin-navigation' => 'Admin\Navigation\Factory\AdminNavigationFactory',
        )
    ),

    'controllers' => array(
        'invokables' => array(
            'Admin\Controller\Auth' => 'Admin\Controller\AuthController',
            'Admin\Controller\Admin' => 'Admin\Controller\AdminController',
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
