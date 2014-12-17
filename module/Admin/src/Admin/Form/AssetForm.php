<?php

namespace Admin\Form;

use Admin\Form\Filter\AssetFormFilter;
use Common\Entity\Asset;
use Common\Manager\AssetManager;
use Common\Manager\UserManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

class AssetForm extends BaseForm
{

    public function init()
    {
        $this->setAttributes([
                'method' => 'post',
                'class' => 'form-horizontal form-bordered'
            ]);

        $this->setInputFilter(new AssetFormFilter($this->getServiceLocator()))->setHydrator(new DoctrineObject($this->getDoctrineEntityManager()))->setObject(new Asset());

        $assetManager = new AssetManager($this->serviceLocator);
        $userManager = new UserManager($this->serviceLocator);

        $this->add([
                'name' => 'user',
                'type' => 'Zend\Form\Element\Select',
                'attributes' => [
                    'class' => 'form-control input-large select2me',
                ],
                'options' => [
                    'label' => 'User',
                    'value_options' => $userManager->getUserForAdminSelect(),
                    'label_attributes' => [
                        'class' => 'col-sm-3 control-label'
                    ]
                ]
            ]);


        $this->add([
                'name' => 'title',
                'type' => 'text',
                'required' => false,
                'attributes' => [
                    'class' => 'form-control input-large',
                    'maxlength' => 255,
                    'placeholder' => 'Title',
                ],
                'options' => [
                    'label' => 'Title',
                    'label_attributes' => [
                        'class' => 'col-sm-3 control-label'
                    ]
                ]
            ]);

        $this->add([
                'name' => 'description',
                'type' => 'Textarea',
                'required' => false,
                'attributes' => [
                    'class' => 'form-control',
                    'placeholder' => 'Description'
                ],
                'options' => [
                    'label' => 'Description',
                    'label_attributes' => [
                        'class' => 'col-sm-3 control-label'
                    ]
                ]
            ]);

        $this->add([
                'name' => 'type',
                'type' => 'Radio',
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
            'attributes' => [
                'type' => 'hidden',
            ]
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

        $this->add([
                'name' => 'url',
                'type' => 'url',
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

        $this->add([
                'type' => 'Zend\Form\Element\Csrf',
                'name' => 'csrf',
                'options' => [
                    'csrf_options' => [
                        'timeout' => 1200
                    ]
                ]
            ]);
    }


}