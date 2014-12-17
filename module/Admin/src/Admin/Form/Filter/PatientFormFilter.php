<?php

namespace Admin\Form\Filter;

use Zend\InputFilter\InputFilter;
use Zend\ServiceManager\ServiceLocatorInterface;

class PatientFormFilter extends InputFilter
{

    public function __construct(ServiceLocatorInterface $serviceLocator)
    {

        $this->add([
                'name' => 'user',
                'required' => true,
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
            ]);

        $this->add([
                'name' => 'title',
                'required' => true,
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    [
                        'name' => 'Zend\Validator\StringLength',
                        'min' => 0,
                        'max' => 511
                    ]
                ]
            ]);

        $this->add([
                'name' => 'description',
                'required' => true
            ]);

        $this->add([
                'name' => 'endDate',
                'validators' => [
                    array(
                        'name' => '\Zend\Validator\Date',
                        'options' => [
                            'format' => $serviceLocator->get('config')['projectData']['options']['dateFormat']
                        ],
                        'break_chain_on_failure' => true
                    ),
                ]
            ]);

        $this->add([
                'name' => 'summa',
                'required' => true,
                'validators' => [
                    [
                        'name' => 'Zend\I18n\Validator\Float',
                    ]
                ]
            ]);

        $this->add([
                'name' => 'check',
                'required' => true,
                'validators' => [
                    [
                        'name' => 'Zend\Validator\Digits'
                    ]
                ]
            ]);

        $this->add([
            'name' => 'published',
            'required' => true,
            'validators' => [
                [
                    'name' => 'Zend\Validator\Digits'
                ]
            ]
        ]);

    }

}