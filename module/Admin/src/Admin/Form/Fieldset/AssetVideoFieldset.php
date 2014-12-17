<?php

namespace Admin\Form\Fieldset;

use Common\Entity\Asset;
use Common\Manager\AssetManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodHydrator;
use Zend\Validator\InArray;

class AssetVideoFieldset extends BaseFieldset
{

    public function __construct(ServiceLocatorInterface $sm)
    {
        $this->setHydratorClass(new ClassMethodHydrator(false));
        parent::__construct($sm, new Asset(), 'assetVideo');
    }

    public function init()
    {

        $this->add([
                'name' => 'type',
                'type' => 'hidden',
                'attributes' => [
                    'value' => 'Video'
                ]
            ]);

        $this->add([
                'name' => 'name',
                'type' => 'hidden'
            ]);

        $this->add([
                'name' => 'url',
                'type' => 'Url',
                'attributes' => [
                    'class' => 'form-control input-large',
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
                'required' => false,
                'name' => 'name',
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
            ],
            [
                'required' => true,
                'name' => 'type',
                'validators' => [
                    array(
                        'name' => 'InArray',
                        'options' => array(
                            'haystack' => $assetManager->getTypesForSelect(),
                            'messages' => array(
                                InArray::NOT_IN_ARRAY => 'Image type select error.',
                            )
                        )
                    )
                ]
            ],
            array(
                'name' => 'url',
                'required' => true,
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                    'validators' => [
                        [
                            'name' => 'Admin\Form\Validator\YoutubeUrl'
                        ]
                ]
            ),
        ];
    }

}