<?php

namespace Admin\Form\Filter;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\InputFilter\InputFilter;
use Admin\Form\Validator\UserStatus;

class LoginFormFilter extends BaseInputFilter
{

    public function init()
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
                            'messages' => [
                                \Zend\Validator\StringLength::TOO_LONG => "The password is more than %max% characters long.",
                                \Zend\Validator\StringLength::TOO_SHORT => 'The password is less than %min% characters long.',
                            ]
                        ],
                        'break_chain_on_failure' => true
                    ],
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

        $this->add([
                'name' => 'remember',
                'required' => false,
            ]);

    }

}