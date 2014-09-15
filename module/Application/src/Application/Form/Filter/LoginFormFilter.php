<?php

namespace Application\Form\Filter;

use Zend\InputFilter\InputFilter;
use Zend\Validator\StringLength;

class LoginFormFilter extends InputFilter
{

    public function __construct()
    {
        $this->add([
                'name' => 'email',
                'required' => 'true',
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    [
                        'name' => '\Zend\Validator\EmailAddress',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min'      => 5,
                            'max'      => 255,
                            'messages' => [
                                \Zend\Validator\EmailAddress::INVALID_FORMAT => 'Email address format is invalid'
                            ]
                        ]
                    ],
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
                        'name'    => 'StringLength',
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