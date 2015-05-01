<?php

namespace Admin\Helper;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;

class AdminAssetPath extends AbstractHelper
{
    protected $adminAssetFolder  = null;

    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        $this->adminAssetFolder = $serviceLocator->get('config')['paths']['assetFolder'];
    }

    public function __invoke()
    {
        return $this->adminAssetFolder;
    }


}