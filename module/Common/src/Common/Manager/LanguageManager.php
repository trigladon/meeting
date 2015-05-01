<?php

namespace Common\Manager;

use Common\DAO\LanguageDAO;
use Common\Entity\Language;

class LanguageManager extends BaseEntityManager
{
    protected $dao = null;

    public function getDAO()
    {
        if ($this->dao === null) {
            $this->dao = new LanguageDAO($this->getServiceLocator());
        }
        return $this->dao;
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
            Language::PUBLISHED,
            Language::UNPUBLISHED,
        ];
    }

    public function revertVisibleLanguage(Language $language)
    {
        switch($language->getPublished())
        {
            case Language::PUBLISHED: $language->setPublished(Language::UNPUBLISHED); break;
            case Language::UNPUBLISHED: $language->setPublished(Language::PUBLISHED); break;
        }

        $this->getDAO()->save($language);
    }

    public function saveLanguage(Language $language)
    {
        $language->setPublished(($language->getPublished() === '0' ? 'no' : 'yes'));
        $this->getDAO()->save($language);
    }

    public function getLanguageForSelect()
    {
        $result = array();

        $defaultLanguage = $this->getServiceLocator()->get('config')['projectData']['defaultLanguage'];

        /** @var $language Language */
        foreach($this->getDAO()->findAll() as $language)
        {
            $result[] = [
                'value' => $language->getPrefix(),
                'label' => $language->getName(),
                'selected' => ($defaultLanguage === $language->getPrefix() ? true : false),
            ];
        }

        return $result;
    }


    public function languageTable()
    {
        return [
            [
                'type' => TableManager::TYPE_TABLE,
                'ajaxRoute' => [
                    'route' => 'admin/default',
                    'parameters' => ['controller' => 'language'],
                ],
                'tableId' => 'admin-list-all-language',
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
                'name' => 'Prefix',
                'percent' => '10%',
                'property' => 'prefix',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_STRING,
                'name' => 'Locale',
                'percent' => '10%',
                'property' => 'locale',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_FUNCTION,
                TableManager::TYPE_COLUMN_FUNCTION => function($name, LanguageManager $manager){
                    return $manager->getPublishedName($name);
                },
                'name' => 'Is Published',
                'percent' => '10%',
                'property' => 'published',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_BUTTON,
                TableManager::TYPE_COLUMN_BUTTON => [
                    [
                        'url' => [
                            'route' => 'admin/default',
                            'parameters' => [
                                [
                                    'value' => 'language',
                                    'name' => 'controller',
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
                        'type' => TableManager::BUTTON_TYPE_FROM_VALUE,
                        'property' => 'published',
                        TableManager::BUTTON_TYPE_FROM_VALUE => [
                            'no' => [
                                'url' => [
                                    'route' => 'admin/default',
                                    'parameters' => [
                                        [
                                            'value' => 'language',
                                            'name' => 'controller',
                                        ],
                                        [
                                            'name' => 'action',
                                            'value' => 'publish',
                                        ],
                                        [
                                            'name' => 'id',
                                            'property' => 'id'
                                        ]
                                    ]
                                ],
                                'modal' => [
                                    'title' => 'Published language',
                                    'description' => 'Do you want published this language?',
                                    'button' => 'Publish',
                                ],
                                'name' => 'Publish',
                                'type' => TableManager::BUTTON_TYPE_DEFAULT,
                                TableManager::BUTTON_TYPE_DEFAULT => 'Publish',
                            ],
                            'yes' => [
                                'url' => [
                                    'route' => 'admin/default',
                                    'parameters' => [
                                        [
                                            'value' => 'language',
                                            'name' => 'controller',
                                        ],
                                        [
                                            'name' => 'action',
                                            'value' => 'publish',
                                        ],
                                        [
                                            'name' => 'id',
                                            'property' => 'id'
                                        ]
                                    ]
                                ],
                                'modal' => [
                                    'title' => 'Unpublished language',
                                    'description' => 'Do you want unpublished this language?',
                                    'button' => 'Unpublish',
                                    'color' => 'btn purple',
                                ],
                                'name' => 'Unpublish',
                                'type' => TableManager::BUTTON_TYPE_DEFAULT,
                                TableManager::BUTTON_TYPE_DEFAULT => 'Unpublish',
                            ],
                        ],
                    ],
                ],
                'name' => 'Actions',
                'percent' => '25%',
                'property' => 'actions',
            ],
        ];
    }


}