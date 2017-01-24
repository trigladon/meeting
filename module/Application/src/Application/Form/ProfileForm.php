<?php

namespace Application\Form;

class ProfileForm extends BaseForm
{

    public function init()
    {

        $this->setInputFilter(new Filter\BaseInputFilter($this->getServiceLocator()));

        $this->setAttributes([
            'method' => 'post',
            'class' => 'form-horizontal form-bordered'
        ]);

        $this->add([
            'name' => 'firstName',
            'attributes' => [
                'type' => 'text',
                'class' => 'form-control input-large',
                'placeholder' => 'First name',
            ],
            'options' => [
                'label' => 'First name',
                'label_attributes' => [
                    'class' => 'col-sm-3 control-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'lastName',
            'attributes' => [
                'type' => 'text',
                'class' => 'form-control input-large',
                'placeholder' => 'Last name',
            ],
            'options' => [
                'label' => 'Last name',
                'label_attributes' => [
                    'class' => 'col-sm-3 control-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'middleName',
            'attributes' => [
                'type' => 'text',
                'class' => 'form-control input-large',
                'placeholder' => 'Middle name',
            ],
            'options' => [
                'label' => 'Middle name',
                'label_attributes' => [
                    'class' => 'col-sm-3 control-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'about',
            'type' => 'Textarea',
            'attributes' => [
                'class' => 'form-control input-large',
                'placeholder' => 'About'
            ],
            'options' => [
                'label' => 'About',
                'label_attributes' => [
                    'class' => 'col-sm-3 control-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'birthday',
            'attributes' => [
                'type' => 'text',
                'class' => 'form-control form-control-inline input-large date-picker',
                'readonly' => 'readonly'
            ],
            'options' => [
                'label' => 'Birthday',
                'label_attributes' => [
                    'class' => 'col-sm-3 control-label'
                ]
            ]
        ]);

        $this->add([
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'csrf',
        ]);

        $this->add([
            'name' => 'submit',
            'attributes' => [
                'class' => 'btn green pull-right',
                'value' => 'Save',
            ],
            'options' => [
                'label' => ' ',
                'label_attributes' => [
                    'class' => 'control-label'
                ]
            ]
        ]);

    }


}