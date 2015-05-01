<?php

namespace Common\Manager;
use Zend\ServiceManager\ServiceLocatorInterface;

class BaseManager
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator = null;

    /**
     * @var TranslatorManager
     */
    protected $translatorManager = null;

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


    /**
     * @return TranslatorManager
     */
    public function getTranslatorManager()
    {
        if ($this->translatorManager === null) {
            $this->translatorManager = new TranslatorManager($this->getServiceLocator());
        }

        return $this->translatorManager;
    }

    /**
     * @param TranslatorManager $translatorManager
     *
     * @return $this
     */
    public function setTranslatorManager(TranslatorManager $translatorManager) {

        $this->translatorManager = $translatorManager;

        return $this;
    }


    public function translate($text, $textDomain = 'default', $locale = null)
    {
        return $this->getTranslatorManager()->translate($text, $textDomain, $locale);
    }

}