<?php

namespace Common;

use Admin\Helper\Languages;
use Admin\Helper\MenuRender;
use Admin\Helper\YoutubeLink;
use Application\Helper\FlashMessage;
use Admin\Helper\AdminAssetPath;
use Admin\Helper\ProjectData;
use Admin\Helper\PageTitle;
use Admin\Helper\Form\FormElementCollection;
use Admin\Helper\Form\FormLabelCollection;

class Module
{


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

    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'renderFlashMessages' => function ($sm) {
                    return new FlashMessage($sm->getServiceLocator());
                },
                'getAdminAssetsPath' => function() {
                    return new AdminAssetPath();
                },
                'getProjectData' => function($sm) {
                    return new ProjectData($sm->getServiceLocator());
                },
                'getPageTitle' => function($sm) {
                    return new PageTitle($sm->getServiceLocator());
                },
                'getYoutubeLink' => function($sm) {
                    return new YoutubeLink();
                },
                'formElementCollection' => function($sm) {
                    return new FormElementCollection();
                },
                'formLabelCollection' => function($sm) {
                    return new FormLabelCollection();
                },
                'projectLanguages' => function($sm) {
                    return new Languages($sm->getServiceLocator());
                },
                'menuRender' => function($sm){
                    return new MenuRender($sm->getServiceLocator());
                }
            )
        );
    }

}
