<?php

namespace Admin\Form;

use Admin\Form\Filter\CityFormFilter;
use Common\Entity\City;
use Common\Manager\CountryManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

class CityForm extends BaseForm
{

    public function init()
    {
        $this->setInputFilter(new CityFormFilter($this->getServiceLocator()))
            ->setHydrator(new DoctrineObject($this->getDoctrineEntityManager()))
            ->setObject(new City());

        $countryManager = new CountryManager($this->getServiceLocator());

        $this->setAttributes([
            'method' => 'post',
            'class' => 'form-horizontal form-bordered'
        ]);


        $this->add([
            'name' => 'country',
            'type' => 'Select',
            'attributes' => [
                'class' => 'form-control input-large select2me'
            ],
            'options' => [
                'label' => 'Country',
                'value_options' => $countryManager->getCountryForAdminSelect(),
                'label_attributes' => [
                    'class' => 'col-sm-3 control-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'name',
            'type' => 'Text',
            'required' => true,
            'attributes' => [
                'class' => 'form-control input-large',
                'maxlength' => 255,
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
            'name' => 'englishName',
            'type' => 'Text',
            'required' => true,
            'attributes' => [
                'class' => 'form-control input-large',
                'maxlength' => 255,
                'placeholder' => 'English city name'
            ],
            'options' => [
                'label' => 'English city name',
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