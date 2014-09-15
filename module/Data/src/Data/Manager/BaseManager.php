<?php

namespace Data\Manager;

use Zend\ServiceManager\ServiceManager;

class BaseManager
{

    /**
     * @var ServiceManager
     */
    protected $serviceManager = null;

    public function __construct(ServiceManager $sm)
    {
        $this->serviceManager = $sm;
    }

    /**
     * @param ServiceManager $serviceManager
     * @return $this
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }

    /**
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }




}