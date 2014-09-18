<?php

namespace Common\Manager;

use Zend\ServiceManager\ServiceLocatorInterface;

class BaseManager
{

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator = null;

    public function __construct(ServiceLocatorInterface $sm)
    {
        $this->serviceLocator = $sm;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return $this
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        return $this;
    }

    /**
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }




}