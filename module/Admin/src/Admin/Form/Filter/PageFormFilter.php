<?php

namespace Admin\Form\Filter;

class PageFormFilter extends BaseInputFilter
{

    public function init()
    {

        $this->add([
            'name' => 'url',
            'required' => true,
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
                ['name' => 'Alpha']
            ],
            'validators' => [
                [
                    'name' => 'Admin\Form\Validator\NoObjectExists',
                    'options' => [
                        'object_repository' => $this->getServiceLocator()->get('doctrine.entitymanager.orm_default')->getRepository('Common\Entity\Page'),
                        'fields' => 'url',
                        'message' => [
                            \Admin\Form\Validator\NoObjectExists::ERROR_OBJECT_FOUND => 'This url is registered in the system.',
                        ]
                    ]
                ],
                //TODO validate with system urls
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