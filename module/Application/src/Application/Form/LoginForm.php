<?php

namespace Application\Form;


use Zend\Form\Form;
use Application\Form\Filter\LoginFormFilter;

class LoginForm extends Form
{

    public function __construct($options = [])
    {
        parent::__construct(__CLASS__, $options);

        $this->setAttribute('method', 'post');
        $this->setInputFilter(new LoginFormFilter());

        $this->add([
                'name' => 'email',
                'attribute' => [
                    'type' => 'email',
                    'required' => 'required'
                ],
                'options' => [
                    'label' => 'E-mail'
                ]
            ]);

        $this->add([
                'name' => 'password',
                'type' => 'password',
                'attribute' => [
                    'required' => 'required',
                ],
                'options' => [
                    'label' => 'Password',
                ]
            ]);


        $this->add([
                'name' => 'remember',
                'type' => 'checkbox',
                'options' => [
                    'label' => 'Remember me'
                ],
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
                    'value' => 'Sign in'
                ]
            ]);
    }



}