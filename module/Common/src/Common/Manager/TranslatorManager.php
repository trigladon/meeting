<?php

namespace Common\Manager;


use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\I18n\Translator\Translator;

class TranslatorManager extends BaseManager
{

    /**
     * @var Translator
     */
    protected $translator;

    public function __construct(ServiceLocatorInterface $sm)
    {
        parent::__construct($sm);
        $this->translator = $sm->get('translator');
    }

    /**
     * @param \Zend\I18n\Translator\Translator $translator
     * @return $this
     */
    public function setTranslator(Translator $translator)
    {
        $this->translator = $translator;

        return $this;
    }

    /**
     * @return \Zend\I18n\Translator\Translator
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    public function translate($text, $textDomain = 'default', $locale = null)
    {
        return $this->getTranslator()->translate($text, $textDomain, $locale);
    }




}