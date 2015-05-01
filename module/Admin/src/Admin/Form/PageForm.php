<?php

namespace Admin\Form;

use Admin\Form\Filter\PageFormFilter;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

class PageForm extends BaseForm
{

    public function init()
    {
        $this->setInputFilter(new PageFormFilter($this->getServiceLocator()))
            ->setHydrator(new DoctrineObject($this->getDoctrineEntityManager()));

        $this->setAttributes([
            'method' => 'post',
            'class' => 'form-horizontal form-bordered'
        ]);

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);

        $this->add([
            'name' => 'translations',
            'type' => 'Zend\Form\Element\Collection',
            'options' => [
                'label' => 'Translations',
                'label_attributes' => [
                    'class' => 'col-sm-3 control-label'
                ],
                'should_create_template' => true,
                'target_element' => array(
                    'type' => 'Admin\Form\Fieldset\PageTranslationsFieldset'
                )
            ]
        ]);

        $this->add([
            'name' => 'assets',
            'type' => 'Zend\Form\Element\Collection',
            'options' => [
                'label' => 'Assets',
                'label_attributes' => [
                    'class' => 'col-sm-3 control-label'
                ],
                'should_create_template' => true,
                'allow_add' => true,
                'target_element' => array(
                    'type' => 'Admin\Form\Fieldset\AssetFieldset'
                )
            ]
        ]);

        $this->add([
            'name' => 'url',
            'type' => 'Text',
            'attributes' => [
                'class' => 'form-control input-large',
                'placeholder' => 'Url',
                'maxlength' => 255,
            ],
            'options' => [
                'label' => 'Page url',
                'label_attributes' => [
                    'class' => 'col-sm-3 control-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'published',
            'type' => 'Checkbox',
            'attributes' => [
                'class' => 'make-switch',
                'data-size' => 'normal',
                'data-on-text' => 'Yes',
                'data-off-text' => 'No',
                'data-on-color' => 'success',
            ],
            'options' => [
                'label' => 'Is Published',
                'label_attributes' => [
                    'class' => 'col-sm-3 control-label'
                ],
            ]
        ]);

        $this->add([
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'csrf',
            'options' => [
                'csrf_options' => [
                    'timeout' => 1200
                ]
            ]
        ]);

    }

}