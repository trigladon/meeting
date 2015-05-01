<?php

namespace Admin\Form\Filter;

class AdvertisingFormFilter extends BaseInputFilter
{

    public function init()
    {
        $this->add([
            'name' => 'place',
            'required' => false
        ]);

        $this->add([
            'name' => 'count',
            'required' => true,
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],

        ]);

        $this->add([
            'name' => 'counter',
            'required' => false,
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],

        ]);

        $this->add([
            'name' => 'url',
            'required' => true,
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
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