<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController',
            'Application\Controller\Auth' => 'Application\Controller\AuthController',
            'Application\Controller\Account' => 'Application\Controller\AccountController',
            'Application\Controller\Search' => 'Application\Controller\SearchController',
        ),
    ),

    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'refresh-captcha' => [
                'type' => 'Literal',
                'options' => array(
                    'route' => '/refresh-captcha',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Auth',
                        'action' => 'refresh-captcha'
                    )
                )
            ],

            'about' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/about',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action' => 'about'
                    )
                )
            ),
            'contacts' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/contacts',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action' => 'contacts'
                    )
                )
            ),
            'sign-in' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/sign-in',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Auth',
                        'action' => 'login'
                    )
                )
            ),
            'sign-up' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/sign-up',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Auth',
                        'action' => 'registration'
                    )
                )
            ),
            'sign-up-success' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/sign-up/success',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Auth',
                        'action' => 'registration-success'
                    )
                )
            ),
            'sign-up-activation' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/sign-up/activation/:code',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Auth',
                        'action' => 'activation',
                        'code' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    )
                )
            ),
            'recovery-password' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/recovery-password',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Auth',
                        'action' => 'recovery-password',
                    )
                )
            ),
            'recovery-password-new' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/recovery-password/:code',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Auth',
                        'action' => 'recovery-password-new',
                        'code' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    )
                )
            ),
            'sign-out' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/sign-out',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Auth',
                        'action' => 'logout'
                    )
                )
            ),
            'account-settings' => [
                'type' => 'literal',
                'options' => [
                    'route' => '/account/settings',
                    'defaults' => [
                        'controller' => 'Application\Controller\Account',
                        'action' => 'settings'
                    ]
                ]
            ],

            'account-settings-edit' => [
                'type' => 'literal',
                'options' => [
                    'route' => '/account/settings/edit',
                    'defaults' => [
                        'controller' => 'Application\Controller\Account',
                        'action' => 'settings-edit'
                    ]
                ]
            ],

            'account-notifications' => [
                'type' => 'literal',
                'options' => [
                    'route' => '/account/notifications',
                    'defaults' => [
                        'controller' => 'Application\Controller\Account',
                        'action' => 'notifications'
                    ]
                ]
            ],

            'account-payments-for-pay' => [
                'type' => 'literal',
                'options' => [
                    'route' => '/account/payments/for-pay',
                    'defaults' => [
                        'controller' => 'Application\Controller\Account',
                        'action' => 'for-pay'
                    ]
                ]
            ],

            'account-payments-history' => [
                'type' => 'literal',
                'options' => [
                    'route' => '/account/payments/history',
                    'defaults' => [
                        'controller' => 'Application\Controller\Account',
                        'action' => 'payments-history'
                    ]
                ]
            ],

            'account-lots-current' => [
                'type' => 'literal',
                'options' => [
                    'route' => '/account/lots/current',
                    'defaults' => [
                        'controller' => 'Application\Controller\Account',
                        'action' => 'lots-current'
                    ]
                ]
            ],

            'account-lots-history' => [
                'type' => 'literal',
                'options' => [
                    'route' => '/account/lots/history',
                    'defaults' => [
                        'controller' => 'Application\Controller\Account',
                        'action' => 'lots-history'
                    ]
                ]
            ],

            'search' => [
                'type' => 'literal',
                'options' => [
                    'route' => '/search',
                    'defaults' => [
                        'controller' => 'Application\Controller\Search',
                        'action' => 'index'
                    ]
                ]
            ],

            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => require __DIR__  .'/template_map.php',
    ),
);
