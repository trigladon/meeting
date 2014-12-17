<?php

namespace Admin\Form;

use Admin\Form\Filter\PatientFormFilter;
use Common\Entity\Patient;
use Common\Manager\PatientManager;
use Common\Manager\UserManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

class PatientForm extends BaseForm
{

    public function init()
    {
        $this->setAttributes([
            'method' => 'post',
            'class' => 'form-horizontal form-bordered'
        ]);

        $this->setInputFilter(new PatientFormFilter($this->getServiceLocator()))->setHydrator(new DoctrineObject($this->getDoctrineEntityManager()))->setObject(new Patient());


        $userManager = new UserManager($this->getServiceLocator());
        $patientManager = new PatientManager($this->getServiceLocator());

        $this->add([
            'name' => 'user',
            'type' => 'Select',
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
            'type' => 'Text',
            'attributes' => [
                'class' => 'form-control input-large',
                'placeholder' => 'Title',
                'maxlength' => 511,
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
            'type' => 'TextArea',
            'attributes' => [
                'class' => 'form-control input-large',
                'placeholder' => 'Description',
            ],
            'options' => [
                'label' => 'Description',
                'label_attributes' => [
                    'class' => 'col-sm-3 control-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'endDate',
            'attributes' => [
                'type' => 'text',
                'class' => 'form-control form-control-inline input-large date-picker',
                'readonly' => 'readonly',
                'placeholder' => 'Last day',
            ],
            'options' => [
                'label' => 'Last day',
                'label_attributes' => [
                    'class' => 'col-sm-3 control-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'summa',
            'type' => 'Text',
            'attributes' => [
                'class' => 'form-control input-large',
                'placeholder' => 'Sum',
            ],
            'options' => [
                'label' => 'Sum',
                'label_attributes' => [
                    'class' => 'col-sm-3 control-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'check',
            'type' => 'Checkbox',
            'attributes' => [
                'class' => 'make-switch',
                'data-size' => 'normal',
                'data-on-text' => 'Yes',
                'data-off-text' => 'No',
                'data-on-color' => 'success',
            ],
            'options' => [
                'label' => 'Patient check',
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
            'name' => 'image',
            'type' => 'Admin\Form\Fieldset\AssetImageFieldset',
            'options' => [
                'label' => 'Patient photo',
                'label_attributes' => [
                    'class' => 'col-sm-3 control-label',
                ]
            ]
        ]);

        $this->add([
            'name' => 'assets',
            'type' => 'Zend\Form\Element\Collection',
            'options' => [
                'label' => 'Assets',
                'label_attributes' => [
                    'class' => 'col-sm-3 control-label'
                ],
                'should_create_template' => true,
                'allow_add' => true,
                'target_element' => array(
                    'type' => 'Admin\Form\Fieldset\AssetFieldset'
                )
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