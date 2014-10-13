<?php

namespace Admin\Helper;

use Zend\ServiceManager\ServiceLocatorInterface;
use \Zend\View\Helper\AbstractHelper;

class ProjectData extends AbstractHelper
{
    /**
     * @var ServiceLocatorInterface|null
     */
    protected $serviceLocator = null;

    protected $config = null;

    public function __construct(ServiceLocatorInterface $serviceLocatorInterface)
    {
        $this->serviceLocator = $serviceLocatorInterface;
    }

    public function __invoke()
    {
        return $this->getConfig();
    }

    /**
     * @return null
     */
    public function getConfig()
    {
        if ($this->config === null) {
            $this->config = $this->getServiceLocator()->get('config');
        }
        return $this->config['projectData'];
    }

    /**
     * @return null
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }




}