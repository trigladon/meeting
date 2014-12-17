<?php

namespace Common;

use Admin\Helper\YoutubeLink;
use Application\Helper\FlashMessage;
use Admin\Helper\AdminAssetPath;
use Admin\Helper\ProjectData;
use Admin\Helper\PageTitle;

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
                }
            )
        );
    }

}
