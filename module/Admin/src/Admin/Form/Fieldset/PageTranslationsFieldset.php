<?php

namespace Admin\Form\Fieldset;

use Common\Entity\PageTranslations;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Zend\ServiceManager\ServiceLocatorInterface;

class PageTranslationsFieldset extends BaseFieldset
{

    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        parent::__construct($serviceLocator, new PageTranslations());
    }

    public function init()
    {
        $this->setObject(new PageTranslations())
            ->setHydrator(new DoctrineObject($this->getServiceLocator()->get('Doctrine\ORM\EntityManager')));

        $this->add([
            'name' => 'title',
            'type' => 'Text',
            'attributes' => [
                'class' => 'form-control input-large',
                'placeholder' => 'Title',
            ],
            'options' => [
                'label' => 'Title',
                'label_attributes' => [
                    'class' => 'col-sm-3 control-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'description',
            'type' => 'TextArea',
            'attributes' => [
                'class' => 'form-control input-large',
                'placeholder' => 'Description',
            ],
            'options' => [
                'label' => 'Description',
                'label_attributes' => [
                    'class' => 'col-sm-3 control-label'
                ],
            ]
        ]);

        $this->add([
            'name' => 'language',
            'type' => 'Admin\Form\Fieldset\LanguageFieldset',
            'options' => [
                'label' => 'Language name',
                'label_attributes' => [
                    'class' => 'col-sm-3 control-label',
                ]
            ]
        ]);

    }

    public function getInputFilterSpecification()
    {
        return [];
    }


}