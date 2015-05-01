<?php
namespace Admin\Form;

use Admin\Form\Filter\AdvertisingFormFilter;
use Common\Entity\Advertising;
use Common\Manager\AdvertisingManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

class AdvertisingForm extends BaseForm
{

    public function init()
    {

        $advertisingManager = new AdvertisingManager($this->getServiceLocator());

        $this->setInputFilter(new AdvertisingFormFilter($this->getServiceLocator()))
            ->setHydrator(new DoctrineObject($this->getDoctrineEntityManager()))
            ->setObject(new Advertising())
        ;

        $this->setAttributes([
            'method' => 'post',
            'class' => 'form-horizontal form-bordered'
        ]);

        $this->add([
            'name' => 'translations',
            'type' => 'Zend\Form\Element\Collection',
            'options' => [
                'label' => 'Translations',
                'label_attributes' => [
                    'class' => 'col-sm-3 control-label'
                ],
                'should_create_template' => true,
                'target_element' => array(
                    'type' => 'Admin\Form\Fieldset\AdvertisingTranslationsFieldset'
                )
            ]
        ]);

        $this->add([
            'name' => 'place',
            'type' => 'Select',
            'attributes' => [
                'class' => 'form-control input-large select2me',
            ],
            'options' => [
                'label' => 'Place',
                'value_options' => $advertisingManager->getPlacesForAdminSelect(),
                'label_attributes' => [
                    'class' => 'col-sm-3 control-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'image',
            'type' => 'Admin\Form\Fieldset\AssetImageFieldset',
            'options' => [
                'label' => 'Image',
                'label_attributes' => [
                    'class' => 'col-sm-3 control-label',
                ]
            ]
        ]);


        $this->add([
            'name' => 'count',
            'type' => 'Number',
            'attributes' => [
                'class' => 'form-control input-large',
                'placeholder' => 'The maximum number of views',
                'maxlength' => 255,
            ],
            'options' => [
                'label' => 'The maximum number of views',
                'label_attributes' => [
                    'class' => 'col-sm-3 control-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'counter',
            'type' => 'Number',
            'attributes' => [
                'class' => 'form-control input-large',
                'placeholder' => 'current number of views',
                'maxlength' => 255,
            ],
            'options' => [
                'label' => 'Current number of views',
                'label_attributes' => [
                    'class' => 'col-sm-3 control-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'url',
            'type' => 'Text',
            'attributes' => [
                'class' => 'form-control input-large',
                'placeholder' => 'Url',
                'maxlength' => 255,
            ],
            'options' => [
                'label' => 'Url',
                'label_attributes' => [
                    'class' => 'col-sm-3 control-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'published',
            'type' => 'Checkbox',
            'attributes' => [
                'class' => 'make-switch',
                'data-size' => 'normal',
                'data-on-text' => 'Yes',
                'data-off-text' => 'No',
                'data-on-color' => 'success',
            ],
            'options' => [
                'label' => 'Is Published',
                'label_attributes' => [
                    'class' => 'col-sm-3 control-label'
                ],
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