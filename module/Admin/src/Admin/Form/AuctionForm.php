<?php

namespace Admin\Form;

use Admin\Form\Filter\AuctionFilter;
use Common\Entity\Auction;
use Common\Manager\AuctionManager;
use Common\Manager\PatientManager;
use Common\Manager\UserManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

class AuctionForm extends BaseForm
{

    public function init()
    {

        $userManager = new UserManager($this->getServiceLocator());
        $patientManager = new PatientManager($this->getServiceLocator());
        $auctionManager = new AuctionManager($this->getServiceLocator());

        $this->setInputFilter(new AuctionFilter($this->getServiceLocator(), $auctionManager))
            ->setHydrator(new DoctrineObject($this->getDoctrineEntityManager()))
            ->setObject(new Auction());

        $this->setAttributes([
            'method' => 'post',
            'class' => 'form-horizontal form-bordered'
        ]);

        $this->add([
            'name' => 'user',
            'type' => 'Select',
            'attributes' => [
                'class' => 'form-control input-large select2me'
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
            'name' => 'patient',
            'type' => 'Select',
            'attributes' => [
                'class' => 'form-control input-large select2me'
            ],
            'options' => [
                'label' => 'Patient',
                'value_options' => $patientManager->getPatientForSelect(),
                'label_attributes' => [
                    'class' => 'col-sm-3 control-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'image',
            'type' => 'Admin\Form\Fieldset\AssetImageFieldset',
            'options' => [
                'label' => 'Photo',
                'label_attributes' => [
                    'class' => 'col-sm-3 control-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'video',
            'type' => 'Admin\Form\Fieldset\AssetVideoFieldset',
            'options' => [
                'label' => 'Video',
                'label_attributes' => [
                    'class' => 'col-sm-3 control-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'startDate',
            'attributes' => [
                'type' => 'text',
                'class' => 'form-control',
                'readonly' => 'readonly'
            ],
            'options' => [
                'label' => 'Start date',
                'label_attributes' => [
                    'class' => 'col-sm-3 control-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'title',
            'type' => 'Text',
            'attributes' => [
                'class' => 'form-control input-large'
            ],
            'options' => [
                'label' => 'Title',
                'label_attributes' => [
                    'class' => 'col-sm-3 control-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'about',
            'type' => 'TextArea',
            'attributes' => [
                'class' => 'form-control input-large'
            ],
            'options' => [
                'label' => 'About',
                'label_attributes' => [
                    'class' => 'col-sm-3 control-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'status',
            'type' => 'Select',
            'attributes' => [
                'class' => 'form-control input-large select2me'
            ],
            'options' => [
                'label' => 'Status',
                'value_options' => $auctionManager->getStatusNameForSelect(),
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


    public function addDateLength(AuctionManager $auctionManager)
    {
        $this->add([
            'name' => 'lengthDate',
            'type' => 'Select',
            'attributes' => [
                'class' => 'form-control input-large select2me'
            ],
            'options' => [
                'label' => 'Auction length day',
                'value_options' => $auctionManager->getAuctionDatesForSelect(),
                'label_attributes' => [
                    'class' => 'col-sm-3 control-label'
                ]
            ]
        ]);

        $this->getInputFilter()->addDateLengthFilter();

        return $this;
    }

    public function addEndDate()
    {
        $this->add([
            'name' => 'endDate',
            'attributes' => [
                'type' => 'text',
                'class' => 'form-control',
                'readonly' => 'readonly'
            ],
            'options' => [
                'label' => 'Start date',
                'label_attributes' => [
                    'class' => 'col-sm-3 control-label'
                ]
            ]
        ]);

        $this->getInputFilter()->addEndDateFilter();

        return $this;
    }

}