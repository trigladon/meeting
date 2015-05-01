<?php

namespace Admin\Form;

use Admin\Form\Filter\LanguageFormFilter;
use Common\Entity\Language;

class LanguageForm extends BaseForm
{

    public function init()
    {
        $this->setInputFilter(new LanguageFormFilter($this->getServiceLocator()))->setObject(new Language());

        $this->setAttributes([
            'method' => 'post',
            'class' => 'form-horizontal form-bordered'
        ]);

        $this->add([
            'name' => 'id',
            'type' => 'Hidden'
        ]);

        $this->add([
            'name' => 'name',
            'type' => 'Text',
            'required' => true,
            'attributes' => [
                'class' => 'form-control input-large',
                'maxlength' => 100,
                'placeholder' => 'Language name',
            ],
            'options' => [
                'label' => 'Language name',
                'label_attributes' => [
                    'class' => 'col-sm-3 control-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'prefix',
            'type' => 'Text',
            'required' => true,
            'attributes' => [
                'class' => 'form-control input-large',
                'maxlength' => 100,
                'placeholder' => 'Language prefix',
            ],
            'options' => [
                'label' => 'Language prefix',
                'label_attributes' => [
                    'class' => 'col-sm-3 control-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'locale',
            'type' => 'Text',
            'required' => true,
            'attributes' => [
                'class' => 'form-control input-large',
                'maxlength' => 100,
                'placeholder' => 'Language locale',
            ],
            'options' => [
                'label' => 'Language locale',
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
                'data-on-color' => 'success'
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