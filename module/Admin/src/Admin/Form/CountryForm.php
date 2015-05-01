<?php

namespace Admin\Form;

use Admin\Form\Filter\CountryFormFilter;
use Common\Entity\Country;

class CountryForm extends BaseForm
{

    public function init()
    {

        $this->setInputFilter(new CountryFormFilter($this->getServiceLocator()))
            ->setObject(new Country());

        $this->setAttributes([
            'method' => 'post',
            'class' => 'form-horizontal form-bordered'
        ]);

        $this->add([
            'name' => 'name',
            'type' => 'Text',
            'required' => true,
            'attributes' => [
                'class' => 'form-control input-large',
                'maxlength' => 255,
                'placeholder' => 'Name',
            ],
            'options' => [
                'label' => 'Name',
                'label_attributes' => [
                    'class' => 'col-sm-3 control-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'englishName',
            'type' => 'Text',
            'required' => true,
            'attributes' => [
                'class' => 'form-control input-large',
                'maxlength' => 255,
                'placeholder' => 'English country name',
            ],
            'options' => [
                'label' => 'English country name',
                'label_attributes' => [
                    'class' => 'col-sm-3 control-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'shortName',
            'type' => 'Text',
            'required' => false,
            'attributes' => [
                'class' => 'form-control input-large',
                'maxlength' => 255,
                'placeholder' => 'Short country name',
            ],
            'options' => [
                'label' => 'Short country name',
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