<?php

namespace Admin\Form\Fieldset;

use Common\Entity\Asset;
use Common\Entity\User;
use Common\Manager\AssetManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Validator\InArray;

class AssetFieldset extends BaseFieldset
{

    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        parent::__construct($serviceLocator, new Asset());
    }

    public function init()
    {
        $assetManager = new AssetManager($this->getServiceLocator());

        $this->add([
            'name' => 'id',
            'required' => false,
            'attributes' => [
                'type' => 'hidden',
                'class' => 'asset-id'
            ]
        ]);

        $this->add([
            'name' => 'type',
            'type' => 'Radio',
            'required' => true,
            'attributes' => [
                'value' => Asset::TYPE_IMAGE,
            ],
            'options' => [
                'label' => 'Type',
                'value_options' => $assetManager->getTypesForSelect(),
            ],
        ]);

        $this->add([
            'name' => 'name',
            'required' => false,
            'attributes' => [
                'type' => 'hidden',
            ]
        ]);

        $this->add([
            'name' => 'upload',
            'required' => false,
            'type' => 'File',
             'options' => [
                'label' => 'File',
                'label_attributes' => [
                    'class' => 'col-sm-3 control-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'url',
            'required' => false,
            'type' => 'url',
            'attributes' => [
                'class' => 'form-control input-youtube-url',
                'maxlength' => 255,
                'placeholder' => 'Url',
            ],
            'options' => [
                'label' => 'Url',
                'label_attributes' => [
                    'class' => 'col-sm-3 control-label'
                ]
            ]
        ]);
    }

    public function getInputFilterSpecification()
    {

        $assetManager = new AssetManager($this->getServiceLocator());

        return [
            [
                'name' => 'id',
                'required' => false,
            ],
            [
                'required' => false,
                'name' => 'name',
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
            ],
            [
                'required' => false,
                'name' => 'type',
                'validators' => [
                        [
                            'name' => 'InArray',
                            'options' => array(
                                'haystack' => $assetManager->getTypes(),
                                'messages' => array(
                                    InArray::NOT_IN_ARRAY => 'Image type select error.',
                                )
                            )
                        ],
                        [
                            'name' => 'Admin\Form\Validator\Asset'
                        ],
                ]
            ],
            [
                'name' => 'upload',
                'required' => false,
                'validators' => [
                    [
                        'name' => 'Admin\Form\Validator\ImageFile',
                        'options' => [
                            'mimeTypes' => ['image/jpeg', 'image/png'],
                        ]
                    ],
                    [
                        'name' => 'Zend\Validator\File\Size',
                        'options' => [
                            'max' => $this->getServiceLocator()->get('config')['projectData']['files']['size']
                        ]
                    ],
                ]
            ],
            [
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
            ]
        ];
    }


}