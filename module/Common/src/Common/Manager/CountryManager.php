<?php

namespace Common\Manager;

use Common\DAO\CityDAO;
use Common\DAO\CountryDAO;
use Common\Entity\City;
use Common\Entity\Country;

class CountryManager extends BaseEntityManager
{

    /** @var CountryDAO */
    protected $DAO = null;

    /** @var CityDAO */
    protected $DAOCity = null;

    /**
     * @return CountryDAO
     */
    public function getDAO()
    {
        if ($this->DAO === null) {
            $this->DAO = new CountryDAO($this->getServiceLocator());
        }
        return $this->DAO;
    }

    /**
     * @return CityDAO
     */
    public function getDAOCity()
    {
        if ($this->DAOCity === null) {
            $this->DAOCity = new CityDAO($this->getServiceLocator());
        }
        return $this->DAOCity;
    }

    public function saveCountry(Country $country)
    {
        $country->setPublished(($country->getPublished() === '0' ? 'no' : 'yes'));
        $this->getDAO()->save($country);
    }

    public function saveCity(City $city)
    {
        $city->setPublished(($city->getPublished() === '0' ? 'no' : 'yes'));
        $this->getDAOCity()->save($city);
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
            Country::PUBLISHED,
            Country::UNPUBLISHED,
        ];
    }

    public function getCountryForAdminSelect()
    {
        $result = [];

        /** @var $country Country */
        foreach($this->getDAO()->findAll() as $country){
            $result[$country->getId()] = $country->getEnglishName();
        }

        return $result;
    }

    public function countryTable()
    {

        return [
            [
                'type' => TableManager::TYPE_TABLE,
                'ajaxRoute' => [
                    'route' => 'admin-country',
                    'parameters' => [],
                ],
                'tableId' => 'admin-list-all-counties',
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
                'property' => 'englishName',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_FUNCTION,
                TableManager::TYPE_COLUMN_FUNCTION => function($published, self $countryManager){
                    return $countryManager->getPublishedName($published);
                },
                'name' => 'Published',
                'percent' => '10%',
                'property' => 'published',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_DATETIME,
                'name' => 'Updated',
                'percent' => '15%',
                'property' => 'updated',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_DATETIME,
                'name' => 'Created',
                'percent' => '15%',
                'property' => 'created',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_BUTTON,
                TableManager::TYPE_COLUMN_BUTTON => [
                    [
                        'url' => [
                            'route' => 'admin-country',
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
                            'route' => 'admin-country',
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

    public function getCitiesListDataForTable($offset, $limit)
    {
        return [
            'count' => $this->getDAOCity()->countAll(),
            'data' => $this->getDAOCity()->findAllJoinOffsetAndLimit($offset, $limit)
        ];
    }

    public function citiesTable()
    {

        return [
            [
                'type' => TableManager::TYPE_TABLE,
                'ajaxRoute' => [
                    'route' => 'admin-city',
                    'parameters' => [],
                ],
                'tableId' => 'admin-list-all-cities',
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
                'percent' => '30%',
                'property' => 'englishName',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_FUNCTION,
                TableManager::TYPE_COLUMN_FUNCTION => function($country, self $countryManager){
                    return $country->getEnglishName();
                },
                'name' => 'Country name',
                'percent' => '30%',
                'property' => 'country',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_FUNCTION,
                TableManager::TYPE_COLUMN_FUNCTION => function($published, self $countryManager){
                    return $countryManager->getPublishedName($published);
                },
                'name' => 'Published',
                'percent' => '5%',
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
                            'route' => 'admin-city',
                            'parameters' => [
                                [
                                    'name' => 'action',
                                    'value' => 'edit-city'
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
                            'route' => 'admin-city',
                            'parameters' => [
                                [
                                    'name' => 'action',
                                    'value' => 'delete-city',
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
                'percent' => '20%',
                'property' => 'actions',
            ],
        ];

    }

}