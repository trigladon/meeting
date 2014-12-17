<?php

namespace Admin\Form\Fieldset;

use Common\Entity\Asset;
use Common\Entity\User;
use Common\Manager\AssetManager;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodHydrator;
use Zend\Validator\InArray;

class AssetImageFieldset extends BaseFieldset implements  InputFilterProviderInterface
{

    public function __construct(ServiceLocatorInterface $sm)
    {
        $this->setHydratorClass(new ClassMethodHydrator(false));
        parent::__construct($sm, new Asset(), 'assetImage');
    }

    public function init()
    {

        $this->add([
            'name' => 'type',
            'type' => 'hidden',
            'attributes' => [
                'value' => 'Image'
            ]
        ]);

        $this->add([
            'name' => 'name',
            'type' => 'hidden'
        ]);

        $this->add([
            'name' => 'upload',
            'type' => 'File',
            'attributes' => [
            ],
            'options' => [
                'label' => 'File',
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
                            'haystack' => $assetManager->getTypes(),
                            'messages' => array(
                                InArray::NOT_IN_ARRAY => 'Image type select error.',
                            )
                        )
                    )
                ]
            ],
            array(
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
            ),
        ];
    }

}