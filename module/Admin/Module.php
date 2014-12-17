<?php

namespace Admin;

use Admin\Form\Fieldset\TestFieldset;
use Admin\Form\Fieldset\UserRoleFieldset;
use Admin\Form\UserForm;
use Common\Manager\UserManager;
use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\Feature\FormElementProviderInterface;

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

}