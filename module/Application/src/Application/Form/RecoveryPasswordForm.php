<?php

namespace Application\Form;

class RecoveryPasswordForm extends BaseForm
{

    public function init()
    {

        $this->setInputFilter(new Filter\RecoveryPasswordFormFilter($this->getServiceLocator()));

        $this->setAttributes([
            'method' => 'post',
            'class' => 'forget-form',
        ]);

        $this->add([
            'name' => 'email',
            'attributes' => [
                'type' => 'email',
                'class' => 'form-control placeholder-no-fix',
                'autocomplete' => 'off',
                'placeholder' => 'E-mail',
            ],
            'options' => [
                'label' => 'E-mail',
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

        $this->add([
            'name' => 'submit',
            'attributes' => [
                'class' => 'btn green pull-right',
                'value' => 'Send',
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