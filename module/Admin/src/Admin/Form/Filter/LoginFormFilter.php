<?php

namespace Admin\Form\Filter;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\InputFilter\InputFilter;
use Admin\Form\Validator\UserStatus;

class LoginFormFilter extends InputFilter
{

    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        $this->add([
                'name' => 'email',
                'required' => 'true',
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
	                array(
		                'name' => '\Zend\Validator\NotEmpty',
	                    'options' =>
	                    array(
		                    'encoding' => 'UTF-8',
		                    'messages' => array(
			                    \Zend\Validator\NotEmpty::IS_EMPTY => 'Please enter e-mail'
		                    )
	                    ),
                        'break_chain_on_failure' => true
	                ),
//                    [
//                        'name' => '\Zend\Validator\EmailAddress',
//                        'options' => [
//                            'encoding' => 'UTF-8',
//                            'min'      => 5,
//                            'max'      => 255,
//                            'messages' => [
//                                \Zend\Validator\EmailAddress::INVALID_FORMAT => 'Email address format is invalid',
//                            ]
//                        ],
//	                    'break_chain_on_failure' => true,
//                    ],
                    [
                        'name' => 'Admin\Form\Validator\UserStatus',
                        'options' => [
                            'object_repository' => $serviceLocator->get('doctrine.entitymanager.orm_default')->getRepository('Common\Entity\User'),
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
                'name' => 'password',
                'required' => 'true',
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    [
                        'name'    => 'Zend\Validator\StringLength',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min'      => 4,
                            'max'      => 15,
                        ],
                    ],
                ]
            ]);

        $this->add([
                'name' => 'remember',
                'required' => false,
            ]);

    }

}