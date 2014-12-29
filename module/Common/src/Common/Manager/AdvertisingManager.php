<?php

namespace Common\Manager;

use Common\DAO\AdvertisingDAO;
use Common\DAO\AdvertisingPlaceDAO;
use Common\DAO\AdvertisingTranslationsDAO;
use Common\Entity\Advertising;
use Common\Entity\AdvertisingPlace;

class AdvertisingManager extends BaseEntityManager
{

    protected $dao = null;
    protected $daoPlace = null;
    protected $daoTranslations = null;

    /**
     * @return AdvertisingDAO|null
     */
    public function getDAO()
    {
        if ($this->dao === null) {
            $this->dao = new AdvertisingDAO($this->getServiceLocator());
        }
        return $this->dao;
    }

    /**
     * @return AdvertisingPlaceDAO|null
     */
    public function getDAOPlace()
    {
        if ($this->daoPlace === null){
            $this->daoPlace = new AdvertisingPlaceDAO($this->getServiceLocator());
        }
        return $this->daoPlace;
    }

    /**
     * @return AdvertisingTranslationsDAO|null
     */
    public function getDAOTranslations()
    {
        if ($this->daoTranslations === null) {
            $this->daoTranslations = new AdvertisingTranslationsDAO($this->getServiceLocator());
        }
        return $this->daoTranslations;
    }

    public function getPublishedNameForSelect()
    {
        $result = [];

        foreach($this->getPublishedNames() as $name){
            $result[$name] = $this->getTranslatorManager()->translate(ucfirst($name));
        }

        return $result;
    }

    public function getPublishedName($name)
    {
        $data = $this->getPublishedNameForSelect();

        if (isset($data[$name])){
            return $data[$name];
        }

        return $name;
    }

    public function getPublishedNames()
    {
        return [
            Advertising::PUBLISHED,
            Advertising::UNPUBLISHED,
        ];
    }

    public function savePlace(AdvertisingPlace $place)
    {
        $place->setPublished($this->getEntityPublishedName($place));
        $this->getDAOPlace()->save($place);
    }

    public function saveAdvertising(Advertising $advertising)
    {
        $assetManager = new AssetManager($this->getServiceLocator());
        $assetManager->saveAsset($advertising->getImage());

        $advertising->setPublished($this->getEntityPublishedName($advertising));
        $this->getDAO()->save($advertising);
    }

    public function getPlaceListDataForTable($offset, $limit)
    {
        return [
            'count' => $this->getDAOPlace()->countAll(),
            'data' => $this->getDAOPlace()->findAllOffsetAndLimit($offset, $limit)
        ];
    }

    /**
     * @param $offset
     * @param $limit
     *
     * @return array
     */
    public function getListDataForTable($offset, $limit)
    {
        return [
            'count' => $this->getDAO()->countAll(),
            'data' => $this->getDAO()->findAllAdvertisingOffsetAndLimit($offset, $limit, DEFAULT_LANGUAGE)
        ];
    }

    public function getPlacesForAdminSelect()
    {
        $result = [];
        /** @var $place \Common\Entity\AdvertisingPlace */
        foreach($this->getDAOPlace()->findAll() as $place) {
            $result[$place->getId()] = '#'.$place->getId().''.$place->getName().' ('.ucfirst($place->getPublished()).')';
        }
        return $result;
    }

    public function advertisingTable()
    {
        return [
            [
                'type' => TableManager::TYPE_TABLE,
                'ajaxRoute' => [
                    'route' => 'admin-advertising',
                    'parameters' => [],
                ],
                'tableId' => 'admin-list-all-advertising',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_STRING,
                'name' => '#id',
                'percent' => '5%',
                'property' => 'id',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_FUNCTION,
                TableManager::TYPE_COLUMN_FUNCTION => function($translations, self $advertisingManager) {
                    /** @var $translations \Common\Entity\AdvertisingTranslations */
                    return $translations->first()->getTitle();
                },
                'name' => 'Title',
                'percent' => '40%',
                'property' => 'translations',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_FUNCTION,
                TableManager::TYPE_COLUMN_FUNCTION => function($place, self $advertisingManager) {
                    /** @var $place \Common\Entity\AdvertisingPlace */
                    return $place->getName();
                },
                'name' => 'Place',
                'percent' => '15%',
                'property' => 'place',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_FUNCTION,
                TableManager::TYPE_COLUMN_FUNCTION => function($published, self $advertisingManager){
                    return $advertisingManager->getPublishedName($published);
                },
                'name' => 'Published',
                'percent' => '5%',
                'property' => 'published',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_DATETIME,
                'name' => 'Updated',
                'percent' => '10%',
                'property' => 'updated',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_DATETIME,
                'name' => 'Created',
                'percent' => '10%',
                'property' => 'created',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_BUTTON,
                TableManager::TYPE_COLUMN_BUTTON => [
                    [
                        'url' => [
                            'route' => 'admin-advertising',
                            'parameters' => [
                                [
                                    'name' => 'action',
                                    'value' => 'edit'
                                ],
                                [
                                    'name' => 'id',
                                    'property' => 'id'
                                ],
                            ]
                        ],
                        'name' => 'Edit',
                        'type' => TableManager::BUTTON_TYPE_DEFAULT,
                        TableManager::BUTTON_TYPE_DEFAULT => 'edit',
                    ],
                    [
                        'url' => [
                            'route' => 'admin-advertising',
                            'parameters' => [
                                [
                                    'name' => 'action',
                                    'value' => 'delete',
                                ],
                                [
                                    'name' => 'id',
                                    'property' => 'id'
                                ]
                            ]
                        ],
                        'modal' => [
                            'title' => 'Title text',
                            'description' => 'Description "" \"" text >text </span>',
                            'button' => 'Remove',
                            'color' => 'btn btn-danger',
                        ],
                        'name' => 'Remove',
                        'type' => TableManager::BUTTON_TYPE_DEFAULT,
                        TableManager::BUTTON_TYPE_DEFAULT => 'delete',
                    ],

                ],
                'name' => 'Actions',
                'percent' => '15%',
                'property' => 'actions',
            ],
        ];
    }

    public function placeTable()
    {
        return [
            [
                'type' => TableManager::TYPE_TABLE,
                'ajaxRoute' => [
                    'route' => 'admin-advertising-place',
                    'parameters' => [],
                ],
                'tableId' => 'admin-list-all-advertising',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_STRING,
                'name' => '#id',
                'percent' => '5%',
                'property' => 'id',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_STRING,
                'name' => 'Name',
                'percent' => '40%',
                'property' => 'name',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_STRING,
                'name' => 'Type',
                'percent' => '10%',
                'property' => 'type',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_FUNCTION,
                TableManager::TYPE_COLUMN_FUNCTION => function($published, self $advertisingManager){
                    return $advertisingManager->getPublishedName($published);
                },
                'name' => 'Published',
                'percent' => '10%',
                'property' => 'published',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_DATETIME,
                'name' => 'Created',
                'percent' => '10%',
                'property' => 'created',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_BUTTON,
                TableManager::TYPE_COLUMN_BUTTON => [
                    [
                        'url' => [
                            'route' => 'admin-advertising-place',
                            'parameters' => [
                                [
                                    'name' => 'action',
                                    'value' => 'edit-place'
                                ],
                                [
                                    'name' => 'id',
                                    'property' => 'id'
                                ],
                            ]
                        ],
                        'name' => 'Edit',
                        'type' => TableManager::BUTTON_TYPE_DEFAULT,
                        TableManager::BUTTON_TYPE_DEFAULT => 'edit',
                    ],
                    [
                        'url' => [
                            'route' => 'admin-advertising-place',
                            'parameters' => [
                                [
                                    'name' => 'action',
                                    'value' => 'delete-place',
                                ],
                                [
                                    'name' => 'id',
                                    'property' => 'id'
                                ]
                            ]
                        ],
                        'modal' => [
                            'title' => 'Title text',
                            'description' => 'Description "" \"" text >text </span>',
                            'button' => 'Remove',
                            'color' => 'btn btn-danger',
                        ],
                        'name' => 'Remove',
                        'type' => TableManager::BUTTON_TYPE_DEFAULT,
                        TableManager::BUTTON_TYPE_DEFAULT => 'delete',
                    ],

                ],
                'name' => 'Actions',
                'percent' => '25%',
                'property' => 'actions',
            ],
        ];
    }


}
