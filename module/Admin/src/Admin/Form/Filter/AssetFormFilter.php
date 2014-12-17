<?php

namespace Admin\Form\Filter;

use Zend\Validator\File\IsImage;
use Zend\Validator\File\Size;
use Zend\Validator\File\MimeType;

class AssetFormFilter extends BaseInputFilter
{

    public function init()
    {
        $this->add([
                'name' => 'type',
                'required' => true,
                'validators' => [
                    [
                        'name' => 'Admin\Form\Validator\Asset'
                    ],
            ]
        ]);

        $this->add([
                'name' => 'title',
                'required' => false,
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
            ]);

        $this->add([
                'name' => 'description',
                'required' => false,
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
            ]);

        $this->add([
                'name' => 'url',
                'required' => false,
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    [
                        'name' => 'Admin\Form\Validator\YoutubeUrl'
                    ]
                ]
            ]);

        $this->add([
                'name' => 'upload',
                'required' => false,
                'validators' => [
                    [
                        'name' => 'Admin\Form\Validator\ImageFile',
                        'options' => [
                            'mimeTypes' => ['image/jpeg', 'image/png'],
                            'empty' => true,
                        ]
                    ],
                    [
                        'name' => 'Zend\Validator\File\Size',
                        'options' => [
                            'max' => $this->getServiceLocator()->get('config')['projectData']['files']['size']
                        ]
                    ],
                ]
            ]);

    }


}