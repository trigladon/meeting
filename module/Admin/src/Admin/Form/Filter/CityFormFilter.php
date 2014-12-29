<?php

namespace Admin\Form\Filter;

class CityFormFilter extends BaseInputFilter
{

    public function init()
    {


        $this->add([
            'name' => 'country',
            'required' => true,
        ]);

        $this->add([
            'name' => 'name',
            'required' => true,
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
        ]);

        $this->add([
            'name' => 'englishName',
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