<?php

return array(
    'controllers'  => array(
        'invokables' => array(

        )
    ),
    'router'       => array(
        'routes' => array(
//            'home'        => array(
//                'type'    => 'Zend\Mvc\Router\Http\Literal',
//                'options' => array(
//                    'route'    => '/',
//                    'defaults' => array(
//                        'controller' => 'Application\Controller\Index',
//                        'action'     => 'index',
//                    ),
//                ),
//            ),
        ),
    ),
    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);
