<?php

namespace Common;

use Zend\Mvc\MvcEvent;
use Zend\Session\Container;
use Zend\ModuleManager\Feature;
use Zend\EventManager\EventInterface;

class Module implements Feature\FormElementProviderInterface, Feature\BootstrapListenerInterface
{

    public function onBootstrap(EventInterface $e)
    {
        $this->logger($e);
        $this->sessionHandler($e);
        $e->getApplication()->getEventManager()->attach(
            $e->getApplication()->getServiceManager()->get('ZfcRbac\View\Strategy\UnauthorizedStrategy')
        );

//        $locale = \Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']);
        $locale = $e->getApplication()->getServiceManager()->get('config')['projectData']['defaultLanguageLocale'];

        $translator = $e->getApplication()->getServiceManager()->get('translator');
        $translator->setLocale($locale)->setFallbackLocale('en_US');

    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/config/autoload_classmap.php',
            ),
        );
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                'admin-navigation' => 'Admin\Navigation\Factory\AdminNavigationFactory',

                'Zend\Authentication\AuthenticationService' => function($sm) {
                    return $sm->get('doctrine.authenticationservice.orm_default');
                },

                'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',

                'Zend\Session\SessionManager' => function($sm) {
                    $config = $sm->get('config');
                    $sessionConfig = new \Zend\Session\Config\SessionConfig();

                    $sessionManager = new \Zend\Session\SessionManager(
                        $sessionConfig->setOptions($config['session']['options']),
                        null,
                        new \Common\Session\SaveHandler\Gateway(
                            $sm->get('doctrine.connection.orm_default'),
                            $config['doctrine']
                        )
                    );
                    $sessionManager->start();

                    return $sessionManager;
                },

                'Common\Service\ErrorLogger' =>  function($sm) {
                    $logger = $sm->get('Zend\Log');
                    $service = new \Common\Service\ErrorLogger($logger);
                    return $service;
                },

                'Zend\Log' => function ($sm) {
                    $filename = 'log_' . date('Y.m.d') . '.txt';
                    $log = new \Zend\Log\Logger();
                    $writer = new \Zend\Log\Writer\Stream('./data/logs/' . $filename);
                    $log->addWriter($writer);

                    return $log;
                },

                'EntityManagerDoctrine' => function($sm) {
                    $entityManager= $sm->get('doctrine.entitymanager.orm_default');
                    $entityManager->getConfiguration()->setQueryCacheImpl($sm->get('cache'));
                    return $entityManager;
                }
            ],
            'invokables' => [
                'cache' => 'Doctrine\Common\Cache\ArrayCache'
            ],
            'abstract_factories' => array(
                'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
                'Zend\Log\LoggerAbstractServiceFactory',
            ),
        ];
    }

    public function getViewHelperConfig()
    {
        return [
            'factories' => array(
                'renderFlashMessages' => function ($sm) {
                    return new \Application\Helper\FlashMessage($sm->getServiceLocator());
                },
                'getAdminAssetsPath' => function($sm) {
                    return new \Admin\Helper\AdminAssetPath($sm->getServiceLocator());
                },
                'getProjectData' => function($sm) {
                    return new \Admin\Helper\ProjectData($sm->getServiceLocator());
                },
                'getPageTitle' => function($sm) {
                    return new \Admin\Helper\PageTitle($sm->getServiceLocator());
                },
                'getYoutubeLink' => function() {
                    return new \Admin\Helper\YoutubeLink();
                },
                'formElementCollection' => function() {
                    return new \Admin\Helper\Form\FormElementCollection();
                },
                'formLabelCollection' => function() {
                    return new \Admin\Helper\Form\FormLabelCollection();
                },
                'projectLanguages' => function($sm) {
                    return new \Admin\Helper\Languages($sm->getServiceLocator());
                },
                'menuRender' => function($sm){
                    return new \Admin\Helper\MenuRender($sm->getServiceLocator());
                },
                'getRouteName' => function($sm){
                    return new \Application\Helper\RouterHelper($sm->getServiceLocator());
                },
                'getAuthService' => function($sm) {
                    return new \Application\Helper\AuthHelper($sm->getServiceLocator());
                }
            )
        ];
    }

    public function getFormElementConfig()
    {
        return [
            'factories' => array(
                //adminka

                //fieldsets
                'Admin\Form\Fieldset\UserRoleFieldset' => function($sm) {
                    return new \Admin\Form\Fieldset\UserRoleFieldset($sm->getServiceLocator());
                },
                'Admin\Form\Fieldset\AssetImageFieldset' => function($sm) {
                    return new \Admin\Form\Fieldset\AssetImageFieldset($sm->getServiceLocator());
                },
                'Admin\Form\Fieldset\AssetVideoFieldset' => function($sm) {
                    return new \Admin\Form\Fieldset\AssetVideoFieldset($sm->getServiceLocator());
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
                'Admin\Form\AuctionForm' => function($sm) {
                    return new \Admin\Form\AuctionForm($sm->getServiceLocator());
                },
                //front

                //form
                'Application\Form\RegistrationForm' => function($sm) {
                    return new \Application\Form\RegistrationForm($sm->getServiceLocator());
                }
            )
        ];
    }

    protected function logger(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attach('dispatch.error', function($event)
        {
            $exception = $event->getResult()->exception;
            if ($exception)
            {
                $sm = $event->getApplication()->getServiceManager();
                $service = $sm->get('Common\Service\ErrorLogger');
                $service->logException($exception);
            }
        });
    }

    private function sessionHandler(MvcEvent $e)
    {
        Container::setDefaultManager(
          $e->getApplication()->getServiceManager()->get('Zend\Session\SessionManager')
        );
    }

}
