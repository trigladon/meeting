<?php

namespace Common\Manager;

use Common\DAO\PageDAO;
use Common\DAO\PageTranslations;
use Common\Entity\Page;

class PageManager extends BaseEntityManager
{

    protected $DAO = null;

    protected $DAOTranslations = null;

    public function getDAO()
    {
        if ($this->DAO === null) {
            $this->DAO = new PageDAO($this->getServiceLocator());
        }
        return $this->DAO;
    }

    public function getDAOTranslations()
    {
        if ($this->DAOTranslations === null) {
            $this->DAOTranslations = new PageTranslations($this->getServiceLocator());
        }

        return $this->DAOTranslations;
    }

    public function getListDataForTable($offset, $limit)
    {
        return [
            'count' => $this->getDAO()->countAll(),
            'data' => $this->getDAO()->findPagesOffsetAndLimit($offset, $limit, $this->getServiceLocator()->get('config')['projectData']['defaultLanguage'])
        ];
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
            Page::PUBLISHED,
            Page::UNPUBLISHED,
        ];
    }

    public function savePage(Page $page)
    {
        $assetsManager = new AssetManager($this->getServiceLocator());
        foreach($page->getAssets() as $asset) {
            $assetsManager->saveAsset($asset);
        }

        $page->setPublished($this->getEntityPublishedName($page));
        $this->getDAO()->save($page);
    }

    public function getPage($url, $locale)
    {
        return $this->getDAO()->findByUrlAndLocale($url, $locale);
    }

    public function pageTable()
    {
        return [
            [
                'type' => TableManager::TYPE_TABLE,
                'ajaxRoute' => [
                    'route' => 'admin/default',
                    'parameters' => ['controller' => 'page'],
                ],
                'tableId' => 'admin-list-all-page',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_STRING,
                'name' => '#id',
                'percent' => '5%',
                'property' => 'id',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_FUNCTION,
                TableManager::TYPE_COLUMN_FUNCTION => function($translations, self $pageManager) {
                    /** @var $translations \Common\Entity\PageTranslations */
                    return $translations->first()->getTitle();
                },
                'name' => 'Title',
                'percent' => '40%',
                'property' => 'translations',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_STRING,
                'name' => 'Url',
                'percent' => '15%',
                'property' => 'url',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_FUNCTION,
                TableManager::TYPE_COLUMN_FUNCTION => function($published, self $pageManager){
                    return $pageManager->getPublishedName($published);
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
                            'route' => 'admin/default',
                            'parameters' => [
                                [
                                    'name' => 'controller',
                                    'value' => 'page',
                                ],
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
                            'route' => 'admin/default',
                            'parameters' => [
                                [
                                    'name' => 'controller',
                                    'value' => 'page',
                                ],
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

}