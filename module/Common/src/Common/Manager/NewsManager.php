<?php

namespace Common\Manager;

use Common\DAO\NewsCategoryDAO;
use Common\DAO\NewsCategoryTranslationsDAO;
use Common\DAO\NewsDAO;
use Common\DAO\NewsTranslationsDAO;
use Common\Entity\BaseEntity;
use Common\Entity\News;
use Common\Entity\NewsCategory;

class NewsManager extends BaseEntityManager
{

    protected $DAO = null;
    protected $DAOTranslations = null;
    protected $DAOCategory = null;
    protected $DAOCategoryTranslations = null;

    /**
     * @return NewsDAO
     */
    public function getDAO()
    {
        if ($this->DAO === null) {
            $this->DAO = new NewsDAO($this->getServiceLocator());
        }
        return $this->DAO;
    }

    /**
     * @return NewsTranslationsDAO
     */
    public function getDAOTranslations()
    {
        if ($this->DAOTranslations === null) {
            $this->DAOTranslations = new NewsTranslationsDAO($this->getServiceLocator());
        }
        return $this->DAOTranslations;
    }

    /**
     * @return NewsCategoryDAO
     */
    public function getDAOCategory()
    {
        if ($this->DAOCategory === null) {
            $this->DAOCategory = new NewsCategoryDAO($this->getServiceLocator());
        }
        return $this->DAOCategory;
    }

    /**
     * @return NewsCategoryTranslationsDAO
     */
    public function getDAOCategoryTranslations()
    {
        if ($this->DAOCategoryTranslations === null) {
            $this->DAOCategoryTranslations = new NewsCategoryTranslationsDAO($this->getServiceLocator());
        }
        return $this->DAOCategoryTranslations;
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
            NewsCategory::PUBLISHED,
            NewsCategory::UNPUBLISHED,
        ];
    }

    public function saveCategory(NewsCategory $category)
    {
        $assetsManager = new AssetManager($this->getServiceLocator());
        foreach($category->getAssets() as $asset) {
            $assetsManager->saveAsset($asset);
        }

        $category->setPublished($this->getEntityPublishedName($category));
        $this->getDAOCategory()->save($category);
    }

    public function saveNews(News $news)
    {
        $assetsManager = new AssetManager($this->getServiceLocator());
        foreach($news->getAssets() as $asset) {
            $assetsManager->saveAsset($asset);
        }

        $news->setPublished($this->getEntityPublishedName($news));
        $this->getDAO()->save($news);
    }

    public function getNewsCategoriesForAdminSelect()
    {
        $result = [];
        foreach($this->getDAOCategory()->findAllCategories(DEFAULT_LANGUAGE) as $category)
        {
            $result[$category->getId()] = $category->getTranslations()->first()->getTitle();
        }
        return $result;
    }

    /**
     * @param $offset
     * @param $limit
     *
     * @return array
     */
    public function getCategoryListDataForTable($offset, $limit)
    {
        return [
            'count' => $this->getDAOCategory()->countAll(),
            'data' => $this->getDAOCategory()
                ->findCategoriesOffsetAndLimit($offset, $limit, DEFAULT_LANGUAGE)
        ];
    }

    /**
     * @param $offset
     * @param $limit
     *
     * @return array
     */
    public function getNewsListDataForTable($offset, $limit)
    {
        return [
            'count' => $this->getDAO()->countAll(),
            'data' => $this->getDAO()
                ->findNewsOffsetAndLimit($offset, $limit, $this->getServiceLocator()->get('config')['projectData']['defaultLanguage'])
        ];
    }

    public function categoryTable()
    {
        return [
            [
                'type' => TableManager::TYPE_TABLE,
                'ajaxRoute' => [
                    'route' => 'admin-category',
                    'parameters' => [],
                ],
                'tableId' => 'admin-list-all-news-category',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_STRING,
                'name' => '#id',
                'percent' => '5%',
                'property' => 'id',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_FUNCTION,
                TableManager::TYPE_COLUMN_FUNCTION => function($translations, self $newsManager) {
                    return $translations->first()->getTitle();
                },
                'name' => 'Title',
                'percent' => '40%',
                'property' => 'translations',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_FUNCTION,
                TableManager::TYPE_COLUMN_FUNCTION => function($user, self $newsManager) {
                    return $user->getFullName();
                },
                'name' => 'User name',
                'percent' => '15%',
                'property' => 'user',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_FUNCTION,
                TableManager::TYPE_COLUMN_FUNCTION => function($published, self $newsManager){
                    return $newsManager->getPublishedName($published);
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
                            'route' => 'admin-category',
                            'parameters' => [
                                [
                                    'name' => 'action',
                                    'value' => 'edit-category'
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
                            'route' => 'admin-category',
                            'parameters' => [
                                [
                                    'name' => 'action',
                                    'value' => 'delete-category',
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

    public function newsTable()
    {
        return [
            [
                'type' => TableManager::TYPE_TABLE,
                'ajaxRoute' => [
                    'route' => 'admin-news',
                    'parameters' => [],
                ],
                'tableId' => 'admin-list-all-news',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_STRING,
                'name' => '#id',
                'percent' => '5%',
                'property' => 'id',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_FUNCTION,
                TableManager::TYPE_COLUMN_FUNCTION => function($translations, self $newsManager) {
                    return $translations->first()->getTitle();
                },
                'name' => 'Title',
                'percent' => '30%',
                'property' => 'translations',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_FUNCTION,
                TableManager::TYPE_COLUMN_FUNCTION => function($category, self $newsManager) {
                    return $category->getTranslations()->first()->getTitle();
                },
                'name' => 'News category',
                'percent' => '25%',
                'property' => 'category',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_FUNCTION,
                TableManager::TYPE_COLUMN_FUNCTION => function($published, self $newsManager){
                    return $newsManager->getPublishedName($published);
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
                            'route' => 'admin-news',
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
                            'route' => 'admin-news',
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


}
