<?php

namespace Admin\Form;

use Admin\Form\Filter\AdvertisingPlaceFormFilter;
use Common\Entity\AdvertisingPlace;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

class AdvertisingPlaceForm extends BaseForm
{

    public function init()
    {
        $this->setInputFilter(new AdvertisingPlaceFormFilter($this->getServiceLocator()))
            ->setHydrator(new DoctrineObject($this->getDoctrineEntityManager()))
            ->setObject(new AdvertisingPlace())
        ;

        $this->setAttributes([
            'method' => 'post',
            'class' => 'form-horizontal form-bordered'
        ]);

        $this->add([
            'name' => 'name',
            'type' => 'Text',
            'attributes' => [
                'class' => 'form-control input-large',
                'maxlength' => 50,
                'placeholder' => 'Name'
            ],
            'options' => [
                'label' => 'Name',
                'label_attributes' => [
                    'class' => 'col-sm-3 control-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'description',
            'type' => 'TextArea',
            'attributes' => [
                'class' => 'form-control input-large',
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
            'name' => 'published',
            'type' => 'Checkbox',
            'attributes' => [
                'class' => 'make-switch',
                'data-size' => 'normal',
                'data-on-text' => 'Yes',
                'data-off-text' => 'No',
                'data-on-color' => 'success'
            ],
            'options' => [
                'label' => 'Is Published',
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