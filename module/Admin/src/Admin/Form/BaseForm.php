<?php

namespace Admin\Form;

use Common\Entity\BaseEntity;
use Common\Manager\LanguageManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Form\Form;

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

    /**
     * Method create new translation for new language
     *
     * @param BaseEntity $entity
     *
     * @return BaseEntity
     * @throws \Exception
     */
    public function extractLanguage(BaseEntity $entity)
    {
        if (!is_callable(array($entity, 'getTranslations'))){
            throw new \Exception('Method "getTranslations" not found');
        }

        $translationClassName = get_class($entity).'Translations';
        $translations = $entity->getTranslations();
        $languageManager = new LanguageManager($this->getServiceLocator());

        foreach($languageManager->getDAO()->findAll() as $language){
            $create = true;
            foreach($translations as $translationEntity){
                 if ($translationEntity !== null && $translationEntity->getLanguage()->getPrefix() === $language->getPrefix()){
                    $create = false;
                }
            }

            if ($create) {
                $entityTranslation = new $translationClassName();
                $entity->addTranslations($entityTranslation->setLanguage($language));
            }
        }

        return $entity;
    }

}