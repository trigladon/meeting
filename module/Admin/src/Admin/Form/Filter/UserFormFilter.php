<?php

namespace Admin\Form\Filter;

class UserFormFilter extends BaseInputFilter
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
    }

}