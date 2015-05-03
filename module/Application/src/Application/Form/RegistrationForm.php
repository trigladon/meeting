<?php

namespace Application\Form;

use Common\Entity\User;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;


class RegistrationForm extends BaseForm
{

    public function init()
    {
        $this->setInputFilter(new Filter\RegistrationFormFilter($this->getServiceLocator()))
            ->setHydrator(new DoctrineObject($this->getDoctrineEntityManager()))
            ->setObject(new User());

        $this->setAttributes([
            'method' => 'post',
            'class' => 'form-horizontal form-bordered'
        ]);

        $this->add([
            'name' => 'firstName',
            'type' => 'Text',
            'required' => true,
            'attributes' => [
                'class' => 'form-control input-large',
                'maxlength' => 50,
                'placeholder' => 'First name'
            ],
            'options' => [
                'label' => 'First name',
                'label_attributes' => [
                    'class' => 'control-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'lastName',
            'type' => 'Text',
            'required' => true,
            'attributes' => [
                'class' => 'form-control input-large',
                'maxlength' => 50,
                'placeholder' => 'Last name'
            ],
            'options' => [
                'label' => 'Last name',
                'label_attributes' => [
                    'class' => 'control-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'email',
            'type' => 'Text',
            'required' => true,
            'attributes' => [
                'class' => 'form-control input-large',
                'maxlength' => 100,
                'placeholder' => 'Email'
            ],
            'options' => [
                'label' => 'Email',
                'label_attributes' => [
                    'class' => 'control-label'
                ]
            ]
        ]);

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
            'name' => 'submit',
            'attributes' => [
                'class' => 'btn green pull-right',
                'value' => 'Create',
            ],
            'options' => [
                'label' => ' ',
                'label_attributes' => [
                    'class' => 'control-label'
                ]
            ]
        ]);

        $this->add([
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'csrf',
        ]);

    }


}