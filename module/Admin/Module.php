<?php

namespace Admin;

use Admin\Helper\PageTitle;
use Admin\Helper\RouteName;
use Admin\Helper\SubMenu;
use Common\Manager\SessionManager;
use Zend\Log\Logger;
use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\MvcEvent;
use Admin\Helper\AdminAssetPath;
use Admin\Helper\ProjectData;
use Zend\Session\Config\SessionConfig;
use Zend\Log\Writer\Stream as LogWriterStream;

class Module
{

    public function init(ModuleManager $moduleManager)
    {
        $moduleManager->getEventManager()->getSharedManager()->attach('Admin', MvcEvent::EVENT_DISPATCH, function(MvcEvent $e){

            $controller = $e->getTarget();
            if ($controller instanceof \Admin\Controller\AuthController){
                $controller->layout('layout/admin-login');
            } else {
                $controller->layout('layout/admin');
            }


        }, 100);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                'Zend\Session\SessionManager' => function ($sm) {
                    $sessionConfig = new SessionConfig();
                    $sessionConfig->setCookieHttpOnly(true);
                    $sessionManager = new SessionManager($sessionConfig);
                    return $sessionManager;
                },
                'Zend\Log' => function () {
                    $filename = 'log_' . date('Y.m.d') . '.txt';
                    $log = new Logger();
                    $writer = new LogWriterStream('./data/logs/' . $filename);
                    $log->addWriter($writer);

                    return $log;
                },
            ]
        ];
    }

    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'AdminAssetsPath' => function() {
                    return new AdminAssetPath();
                },
                'getProjectData' => function($sm) {
                    return new ProjectData($sm->getServiceLocator());
                },
                'getRouteName' => function($sm) {
                    return new RouteName($sm->getServiceLocator());
                },
                'getPageTitle' => function($sm) {
                    return new PageTitle($sm->getServiceLocator());
                }
            )
        );
    }

}