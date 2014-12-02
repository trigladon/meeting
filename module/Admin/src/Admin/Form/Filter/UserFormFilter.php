<?php

namespace Admin\Form\Filter;

use Zend\InputFilter\InputFilter;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Validator\Date;

class UserFormFilter extends InputFilter
{

    public function __construct(ServiceLocatorInterface $sm)
    {
        $this->add([
                'name' => 'birthday',
                'required' => 'true',
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    array(
                        'name' => '\Zend\Validator\Date',
                        'options' => [
                            'format' => $sm->get('config')['projectData']['options']['dateFormat']
                        ],
                        'break_chain_on_failure' => true
                    ),
                    ]
            ]);
    }

}