<?php

namespace Admin\Helper;

use Common\Manager\LanguageManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;

class Languages extends AbstractHelper
{

    protected $serviceLocator = null;

    protected $active = null;

    protected $languages = null;

    public function __construct(ServiceLocatorInterface $serviceLocatorInterface)
    {
        $this->serviceLocator = $serviceLocatorInterface;
    }

    public function __invoke()
    {
        return $this->getLanguages();
    }

    /**
     * @return mixed
     */
    protected function getLanguages()
    {
        if ($this->languages === null) {
            $languageManager = new LanguageManager($this->serviceLocator);
            $this->languages = $languageManager->getDAO()->findAll();
        }

        return $this->languages;
    }



}