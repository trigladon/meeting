<?php

namespace Application\Form\Filter;

use Zend\Validator\StringLength;


class ProfileFormFilter extends BaseInputFilter
{

    public function init()
    {

        $this->add([
            'name' => 'birthday',
            'required' => true,
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim']
            ],
            'validators' => [
                array(
                    'name' => '\Zend\Validator\Date',
                    'options' => [
                        'format' => $this->getServiceLocator()->get('config')['projectData']['options']['dateFormat']
                    ],
                    'break_chain_on_failure' => true
                ),
            ]
        ]);


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
            'name' => 'middleName',
            'required' => false,
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
            'name' => 'about',
            'required' => false,
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
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