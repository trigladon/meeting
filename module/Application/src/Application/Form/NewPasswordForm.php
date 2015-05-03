<?php

namespace Application\Form;


class NewPasswordForm extends BaseForm
{

    public function init()
    {

        $this->setInputFilter(new Filter\NewPasswordFormFilter($this->getServiceLocator()));

        $this->add([
            'name' => 'password',
            'type' => 'password',
            'required' => true,
            'attributes' => [
                'class' => 'form-control input-large',
                'maxlength' => 15,
                'minlength' => 3,
                'placeholder' => 'Password'
            ],
            'options' => [
                'label' => 'Password',
                'label_attributes' => [
                    'class' => 'control-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'confirmPassword',
            'type' => 'password',
            'required' => true,
            'attributes' => [
                'class' => 'form-control input-large',
                'maxlength' => 15,
                'minlength' => 3,
                'placeholder' => 'Confirm password'
            ],
            'options' => [
                'label' => 'Confirm password',
                'label_attributes' => [
                    'class' => 'control-label'
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