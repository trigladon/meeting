<?php

namespace Admin\Form\Filter;


use Common\Manager\AuctionManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Validator\InArray;

class AuctionFilter extends BaseInputFilter
{
    /** @var AuctionManager|null  */
    protected $auctionManager = null;

    public function __construct(ServiceLocatorInterface $serviceLocator, AuctionManager $auctionManager )
    {
        $this->auctionManager = $auctionManager;
        parent::__construct($serviceLocator);
    }

    public function init()
    {
        $this->add([
            'name' => 'user',
            'required' => true,
        ]);

        $this->add([
            'name' => 'patient',
            'required' => true,
        ]);

        $this->add([
            'name' => 'status',
            'required' => true,
            'validators' => [
                array(
                    'name' => 'InArray',
                    'options' => array(
                        'haystack' => $this->auctionManager->getStatus(),
                        'messages' => array(
                            InArray::NOT_IN_ARRAY => 'Status type select error.',
                        )
                    )
                )
            ]
        ]);

        $this->add([
            'name' => 'startDate',
            'required' => true,
//            'validators' => [
//                array(
//                    'name' => '\Zend\Validator\Date',
//                    'options' => [
//                        'format' => $this->getServiceLocator()->get('config')['projectData']['options']['dateTimeFormat']
//                    ],
//                    'break_chain_on_failure' => true
//                ),
//            ]
        ]);

        $this->add([
            'name' => 'title',
            'required' => true,
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
        ]);

        $this->add([
            'name' => 'about',
            'required' => true,
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
        ]);

    }

    public function addDateLengthFilter()
    {
        $this->add([
            'name' => 'lengthDate',
            'required' => true,
            'validators' => [
                array(
                    'name' => 'InArray',
                    'options' => array(
                        'haystack' => $this->auctionManager->getAuctionDates(),
                        'messages' => array(
                            InArray::NOT_IN_ARRAY => 'Auction dates select error.',
                        )
                    )
                )
            ]
        ]);
    }

    public function addEndDateFilter()
    {
        $this->add([
            'name' => 'endDate',
            'required' => true,
            'validators' => [
                array(
                    'name' => '\Zend\Validator\Date',
                    'options' => [
                        'format' => $this->getServiceLocator()->get('config')['projectData']['options']['dateTimeFormat']
                    ],
                    'break_chain_on_failure' => true
                ),
            ]
        ]);
    }

}