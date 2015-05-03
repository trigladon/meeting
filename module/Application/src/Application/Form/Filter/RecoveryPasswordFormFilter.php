<?php

namespace Application\Form\Filter;

use Admin\Form\Validator\UserStatus;

class RecoveryPasswordFormFilter extends BaseInputFilter
{

    public function init()
    {

        $this->add([
            'name' => 'email',
            'required' => true,
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => '\Zend\Validator\NotEmpty',
                    'options' =>
                        [
                            'encoding' => 'UTF-8',
                            'messages' => [
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Please enter e-mail'
                            ]
                        ]
                ],
                [
                    'name' => '\Zend\Validator\StringLength',
                    'options' =>
                        [
                            'encoding' => 'UTF-8',
                            'min' => 4,
                            'max' => 250,
                            'messages' => [
                                \Zend\Validator\StringLength::TOO_LONG => 'E-mail max length is 250 symbols',
                                \Zend\Validator\StringLength::TOO_SHORT => 'E-mail min length is 4 symbols'
                            ]
                        ]
                ],
                [
                    'name' => 'Admin\Form\Validator\UserStatus',
                    'options' => [
                        'object_repository' => $this->getServiceLocator()->get('doctrine.entitymanager.orm_default')->getRepository('Common\Entity\User'),
                        'fields' => 'email',
                        'messages' => [
                            UserStatus::ERROR_STATUS_NO_ACTIVE => 'Your account is not active',
                            UserStatus::ERROR_STATUS_BANNED => "You are banned",
                        ]
                    ]
                ]
            ]
        ]);

        $this->add([
            'name' => 'csrf',
            'required' => true,
            'filters' => [
                ['name' => 'Zend\Filter\StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'Zend\Validator\Csrf',
                    'options' => [
                        'timeout' => $this->getServiceLocator()->get('config')['projectData']['csrf']['timeout'],
                        'messages' => [
                            \Zend\Validator\Csrf::NOT_SAME => 'The form submitted did not originate from the expected site'
                        ],
                    ],
                    'break_chain_on_failure' => true,
                ]
            ]
        ]);

    }


}