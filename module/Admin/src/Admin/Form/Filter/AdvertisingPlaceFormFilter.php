<?php

namespace Admin\Form\Filter;

class AdvertisingPlaceFormFilter extends BaseInputFilter
{

    public function init()
    {
        $this->add([
            'name' => 'name',
            'required' => true,
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim']
            ]

        ]);

        $this->add([
            'name' => 'description',
            'required' => true,
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