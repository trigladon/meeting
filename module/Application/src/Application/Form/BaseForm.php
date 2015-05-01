<?php

namespace Application\Form;

use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorInterface;

class BaseForm extends Form
{


    protected $doctrineEntityManager = null;

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


    /**
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getDoctrineEntityManager()
    {
        if ($this->doctrineEntityManager === null) {
            $this->doctrineEntityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
        return $this->doctrineEntityManager;
    }

    /**
     * @param null $doctrineEntityManager
     *
     * @return $this
     */
    protected function setDoctrineEntityManager($doctrineEntityManager)
    {
        $this->doctrineEntityManager = $doctrineEntityManager;

        return $this;
    }

}