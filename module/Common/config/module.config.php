<?php

return [
    'doctrine' => array(
        'driver' => array(
            'data_entities' => array(
                'class' =>'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Common/Entity')
            ),

            'orm_default' => array(
                'drivers' => array(
                    'Common\Entity' => 'data_entities'
                )
            )
        ),
        'authentication' => array(
            'orm_default' => array(
                'object_manager' => 'Doctrine\ORM\EntityManager',
                'identity_class' => 'Common\Entity\User',
                'identity_property' => 'email',
                'credential_property' => 'password',
                'credential_callable' => array('Common\Manager\AuthManager', 'credentialCallable')
            ),
        ),
    ),

    'translator' => array(
        'locale' => 'en_US',
        //'locale' => 'ru_RU',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'form_elements' => [
        'factories' => array(
            //adminka

                //fieldsets
                'Admin\Form\Fieldset\UserRoleFieldset' => function($sm) {
                    return new \Admin\Form\Fieldset\UserRoleFieldset($sm->getServiceLocator());
                },
                'Admin\Form\Fieldset\AssetImageFieldset' => function($sm) {
                    return new \Admin\Form\Fieldset\AssetImageFieldset($sm->getServiceLocator());
                },
                'Admin\Form\Fieldset\AssetFieldset' => function($sm) {
                    return new \Admin\Form\Fieldset\AssetFieldset($sm->getServiceLocator());
                },
                'Admin\Form\Fieldset\PageTranslationsFieldset' => function($sm) {
                    return new \Admin\Form\Fieldset\PageTranslationsFieldset($sm->getServiceLocator());
                },
                'Admin\Form\Fieldset\LanguageFieldset' => function($sm) {
                    return new \Admin\Form\Fieldset\LanguageFieldset($sm->getServiceLocator());
                },
                'Admin\Form\Fieldset\NewsCategoryTranslationsFieldset' => function($sm) {
                    return new \Admin\Form\Fieldset\NewsCategoryTranslationsFieldset($sm->getServiceLocator());
                },
                'Admin\Form\Fieldset\NewsTranslationsFieldset' => function($sm) {
                    return new \Admin\Form\Fieldset\NewsTranslationsFieldset($sm->getServiceLocator());
                },
                'Admin\Form\Fieldset\AdvertisingTranslationsFieldset' => function($sm) {
                    return new \Admin\Form\Fieldset\AdvertisingTranslationsFieldset($sm->getServiceLocator());
                },

                //form
                'Admin\Form\UserForm' => function($sm) {
                    return new \Admin\Form\UserForm($sm->getServiceLocator());
                },
                'Admin\Form\AssetForm' => function($sm) {
                    return new \Admin\Form\AssetForm($sm->getServiceLocator());
                },
                'Admin\Form\PatientForm' => function($sm) {
                    return new \Admin\Form\PatientForm($sm->getServiceLocator());
                },
                'Admin\Form\LanguageForm' => function($sm) {
                    return new \Admin\Form\LanguageForm($sm->getServiceLocator());
                },
                'Admin\Form\PageForm' => function($sm) {
                    return new \Admin\Form\PageForm($sm->getServiceLocator());
                },
                'Admin\Form\CountryForm' => function($sm) {
                    return new \Admin\Form\CountryForm($sm->getServiceLocator());
                },
                'Admin\Form\CityForm' => function($sm) {
                    return new \Admin\Form\CityForm($sm->getServiceLocator());
                },
                'Admin\Form\NewsCategoryForm' => function($sm) {
                    return new \Admin\Form\NewsCategoryForm($sm->getServiceLocator());
                },
                'Admin\Form\NewsForm' => function($sm) {
                    return new \Admin\Form\NewsForm($sm->getServiceLocator());
                },
                'Admin\Form\AdvertisingPlaceForm' => function($sm) {
                    return new \Admin\Form\AdvertisingPlaceForm($sm->getServiceLocator());
                },
                'Admin\Form\AdvertisingForm' => function($sm) {
                    return new \Admin\Form\AdvertisingForm($sm->getServiceLocator());
                },
                'Admin\Form\FeedbackAnswerForm' => function($sm) {
                    return new \Admin\Form\FeedbackAnswerForm($sm->getServiceLocator());
                },

            //front
        )
    ],
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'factories' => [
            'admin-navigation' => 'Admin\Navigation\Factory\AdminNavigationFactory',

            'Zend\Authentication\AuthenticationService' => function($sm) {
                //return $sm->get('Common\Authentication\Adapter\AuthObjectRepository');
                return $sm->get('doctrine.authenticationservice.orm_default');
            },
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
            'Zend\Session\SessionManager' => function($sm) {
                $config = $sm->get('config');
                $sessionConfig = new Zend\Session\Config\SessionConfig();

                $sessionManager = new \Zend\Session\SessionManager(
                    $sessionConfig->setOptions($config['session']['options']),
                    null,
                    new Common\Session\SaveHandler\Gateway(
                        $sm->get('doctrine.connection.orm_default'),
                        $config['doctrine']
                    )
                );
                $sessionManager->start();

                return $sessionManager;
            },
            'Zend\Log' => function ($sm) {
                $filename = 'log_' . date('Y.m.d') . '.txt';
                $log = new Zend\Log\Logger();
                $writer = new Zend\Log\Writer\Stream('./data/logs/' . $filename);
                $log->addWriter($writer);

                return $log;
            },
        ]
    ),

];