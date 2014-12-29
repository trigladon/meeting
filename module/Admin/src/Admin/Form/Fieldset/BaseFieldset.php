<?php

namespace Admin\Form\Fieldset;

use Common\Entity\BaseEntity;
use Zend\Form\Fieldset;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\AbstractHydrator;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodHydrator;

abstract class BaseFieldset extends Fieldset implements ServiceLocatorAwareInterface, InputFilterProviderInterface
{

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator = null;

    /**
     * @var AbstractHydrator
     */
    protected $hydratorClass = null;

    public function __construct(ServiceLocatorInterface $serviceLocator, BaseEntity $entity = null, $name = null)
    {
        parent::__construct($name);
        $this->serviceLocator = $serviceLocator;
        $this->setHydrator($this->getHydratorClass());

        if ($entity !== null) {
            $this->setObject($entity);
        }
    }

    /**
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator->getServiceLocator();
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
     * @return AbstractHydrator
     */
    public function getHydratorClass()
    {
        if ($this->hydratorClass === null){
            $this->hydratorClass = new ClassMethodHydrator(false);
        }
        return $this->hydratorClass;
    }


    public function setHydratorClass(AbstractHydrator $classHydrator)
    {
        $this->hydratorClass = $classHydrator;

        return $this;
    }

    abstract public function getInputFilterSpecification();

}
