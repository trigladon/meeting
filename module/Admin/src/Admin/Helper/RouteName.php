<?php

namespace Admin\Helper;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;
use Zend\Mvc\Router\Http\TreeRouteStack;

class RouteName extends AbstractHelper
{

    private $serviceLocator = null;

    public function __construct(ServiceLocatorInterface $serviceLocatorInterface)
    {
        $this->serviceLocator = $serviceLocatorInterface;
    }

    public function __invoke()
    {

    }

    /**
     * @return null
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }



}
