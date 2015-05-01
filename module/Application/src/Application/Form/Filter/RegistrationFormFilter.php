<?php

namespace Application\Form\Filter;

use Zend\Validator\StringLength;

class RegistrationFormFilter extends BaseInputFilter
{

    public function init()
    {

        $this->add([
            'name' => 'firstName',
            'required' => true,
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'Zend\Validator\StringLength',
                    'options' => [
                        'min' => 2,
                        'max' => 50,
                        'messages' => [
                            StringLength::TOO_LONG => "The first name is more than %max% characters long.",
                            StringLength::TOO_SHORT => 'The first name is less than %min% characters long.',
                        ]
                    ],
                    'break_chain_on_failure' => true
                ]
            ]
        ]);

        $this->add([
            'name' => 'lastName',
            'required' => true,
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'Zend\Validator\StringLength',
                    'options' => [
                        'min' => 2,
                        'max' => 50,
                        'messages' => [
                            StringLength::TOO_LONG => "The last name is more than %max% characters long.",
                            StringLength::TOO_SHORT => 'The last name is less than %min% characters long.',
                        ]
                    ],
                    'break_chain_on_failure' => true
                ]
            ]
        ]);

        $this->add([
            'name' => 'email',
            'required' => true,
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'Zend\Validator\EmailAddress',
                    'options' => [
                        'message' => [
                            \Zend\Validator\EmailAddress::INVALID => 'Email is invalid.',
                        ],
                        'break_chain_on_failure' => true
                    ]
                ],
                [
                    'name' => 'DoctrineModule\Validator\NoObjectExists',
                    'options' => [
                        'object_repository' => $this->getServiceLocator()->get('doctrine.entitymanager.orm_default')->getRepository('Common\Entity\User'),
                        'fields' => 'email',
                        'message' => [
                            \DoctrineModule\Validator\NoObjectExists::ERROR_OBJECT_FOUND => 'This email is already registered in the system.',
                        ]
                    ]
                ],
            ]
        ]);

        $this->add([
            'name' => 'password',
            'required' => true,
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'Zend\Validator\StringLength',
                    'options' => [
                        'min' => 6,
                        'max' => 15,
                        'messages' => [
                            StringLength::TOO_LONG => "The password is more than %max% characters long.",
                            StringLength::TOO_SHORT => 'The password is less than %min% characters long.',
                        ]
                    ],
                    'break_chain_on_failure' => true
                ],
                [
                    'name' => 'Zend\Validator\Identical',
                    'options' => array(
                        'token' => 'confirmPassword',
                        'messages' => array(
                            \Zend\Validator\Identical::NOT_SAME => 'The password is not same.'
                        )
                    )
                ]
            ]
        ]);

        $this->add([
            'name' => 'confirmPassword',
            'required' => true,
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'Zend\Validator\StringLength',
                    'options' => [
                        'min' => 6,
                        'max' => 15,
                        'messages' => [
                            StringLength::TOO_LONG => "The confirm password is more than %max% characters long.",
                            StringLength::TOO_SHORT => 'The confirm password is less than %min% characters long.',
                        ]
                    ],
                    'break_chain_on_failure' => true
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
