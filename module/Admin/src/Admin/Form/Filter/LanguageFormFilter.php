<?php

namespace Admin\Form\Filter;

class LanguageFormFilter extends BaseInputFilter
{

    public function init()
    {

        $this->add([
            'name' => 'id',
            'required' => false,
        ]);

        $this->add([
            'name' => 'name',
            'required' => true,
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => '\Zend\Validator\StringLength',
                    'options' =>
                        [
                            'encoding' => 'UTF-8',
                            'min' => 2,
                            'max' => 100,
                            'messages' => [
                                \Zend\Validator\StringLength::TOO_LONG => 'Language name max length is 100 symbols',
                                \Zend\Validator\StringLength::TOO_SHORT => 'Language name min length is 2 symbols'
                            ]
                        ]
                ],
                [
                    'name' => 'Admin\Form\Validator\NoObjectExists',
                    'options' => [
                        'object_repository' => $this->getServiceLocator()->get('doctrine.entitymanager.orm_default')->getRepository('Common\Entity\Language'),
                        'fields' => 'name',
                        'message' => [
                            \Admin\Form\Validator\NoObjectExists::ERROR_OBJECT_FOUND => 'This language name is registered in the system.',
                        ]
                    ]
                ],

            ]
        ]);

        $this->add([
            'name' => 'prefix',
            'required' => true,
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => '\Zend\Validator\StringLength',
                    'options' =>
                        [
                            'encoding' => 'UTF-8',
                            'min' => 0,
                            'max' => 4,
                            'messages' => [
                                \Zend\Validator\StringLength::TOO_LONG => 'Prefix max length is 4 symbols',
                                \Zend\Validator\StringLength::TOO_SHORT => 'Prefix min length is 1 symbols'
                            ]
                        ]
                ],
                [
                    'name' => 'Admin\Form\Validator\NoObjectExists',
                    'options' => [
                        'object_repository' => $this->getServiceLocator()->get('doctrine.entitymanager.orm_default')->getRepository('Common\Entity\Language'),
                        'fields' => 'prefix',
                        'message' => [
                            \Admin\Form\Validator\NoObjectExists::ERROR_OBJECT_FOUND => 'This language prefix is registered in the system.',
                        ]
                    ]
                ],
            ]
        ]);

        $this->add([
            'name' => 'locale',
            'required' => true,
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => '\Zend\Validator\StringLength',
                    'options' =>
                        [
                            'encoding' => 'UTF-8',
                            'min' => 0,
                            'max' => 7,
                            'messages' => [
                                \Zend\Validator\StringLength::TOO_LONG => 'Locale max length is 7 symbols',
                                \Zend\Validator\StringLength::TOO_SHORT => 'Locale min length is 1 symbols'
                            ]
                        ]
                ],
                [
                    'name' => 'Admin\Form\Validator\NoObjectExists',
                    'options' => [
                        'object_repository' => $this->getServiceLocator()->get('doctrine.entitymanager.orm_default')->getRepository('Common\Entity\Language'),
                        'fields' => 'locale',
                        'message' => [
                            \Admin\Form\Validator\NoObjectExists::ERROR_OBJECT_FOUND => 'This language locale is registered in the system.',
                        ]
                    ]
                ],

            ]
        ]);

        $this->add([
            'name' => 'published',
            'required' => false,
            'validators' => [
                [
                    'name' => 'Zend\Validator\Digits'
                ]
            ]
        ]);

    }

}