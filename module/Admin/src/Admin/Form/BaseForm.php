<?php

namespace Admin\Form;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Form\Form;

class BaseForm extends Form
{

    protected $serviceLocator = null;

    public function __construct(ServiceLocatorInterface $serviceLocator, $name = null)
    {
        $this->serviceLocator = $serviceLocator;
        parent::__construct($name);
    }

    /**
     * @return null|ServiceLocatorInterface
     */
    protected function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * @param null|ServiceLocatorInterface $serviceLocator
     *
     * @return $this
     */
    protected function setServiceLocator($serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        return $this;
    }



}