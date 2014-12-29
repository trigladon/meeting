<?php

namespace Common\Manager;

use Admin\Form\Validator\YoutubeUrl;
use Common\DAO\AssetDAO;
use Common\Entity\Asset;
use Common\Entity\User;

class AssetManager extends BaseEntityManager
{

    protected $dao = null;
    protected $imagine = null;
    protected $defaultFolder = 'default.asset.dir';

    protected $imageSize = [
        'profile' => [
            'thumb' => [
                'width' => '300',
                'height' => '300',
                'prefix' => Asset::THUMB_PREFIX,
            ],
            'default' => [
                'width' => '500',
                'height' => '500'
            ]
        ],
        'default' => [
            'thumb' => [
                'width' => '300',
                'height' => '300',
                'prefix' => Asset::THUMB_PREFIX,
            ],
            'default' => [
                'width' => '500',
                'height' => '500'
            ]
        ],
    ];

    protected $imagePath = Asset::IMAGE_PATH;

    public function getDAO()
    {
        if ($this->dao === null) {
            $this->dao = new AssetDAO($this->getServiceLocator());
        }

        return $this->dao;
    }

    public function getTypesForSelect()
    {
        $result = [];
        foreach($this->getTypes() as $type){
            $result[$type] = $this->getTranslatorManager()->translate(ucfirst($type));
        }
        return $result;
    }

    public function getTypes()
    {
        return [
            Asset::TYPE_IMAGE,
            Asset::TYPE_VIDEO,
        ];
    }

    public function getTypesName($idType)
    {
        $types = $this->getTypesForSelect();

        if (isset($types[$idType])) {
            return $types[$idType];
        }
        return $idType;
    }

    public function getDeletedNameForSelect()
    {
        return [
            User::DELETED_NO => $this->getTranslatorManager()->translate('No'),
            User::DELETED_YES => $this->getTranslatorManager()->translate('Yes'),
        ];
    }

    public function getDeletedNameByIdDeleted($idDeleted)
    {
        $selectData = $this->getDeletedNameForSelect();

        if (isset($selectData[$idDeleted])) {
            return $selectData[$idDeleted];
        }
        return $idDeleted;
    }

    public function saveAsset(Asset $asset, $fileType = 'default')
    {
        $asset = $this->saveFile($asset, $fileType);
        $asset = $this->youtubeId($asset);
        $this->getDAO()->save($asset);

    }

    protected function saveFile(Asset $asset, $fileType = 'default')
    {
        if (isset($asset->getUpload()['tmp_name']) && $asset->getUpload()['tmp_name']) {

            $fileName = $this->createFileName($asset);
            $directory = $this->createDir($asset);

            foreach($this->imageSize[$fileType] as $size) {
                $this->getImagine()->open($asset->getUpload()['tmp_name'])
                    ->thumbnail(new \Imagine\Image\Box($size['width'], $size['height']))
                    ->save($directory.'/'.(isset($size['prefix']) ? $size['prefix'] : '').$fileName);
            }
            $this->removeFile($asset);
            $asset->setRealName($asset->getUpload()['name']);
            $asset->setName($fileName);
            $asset->setDirectory($asset->getUser()->getFolderName());
        }

        return $asset;
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
            'data' => $this->getDAO()->findAllOffsetAndLimit($offset, $limit)
        ];
    }

    protected function youtubeId(Asset $asset)
    {
        if (trim($asset->getUrl()))
        {
            if (preg_match(YoutubeUrl::YOUTUBE_REGEXP, $asset->getUrl(), $data))
            {
                $asset->setUrl($data[1]);
            }
        }

        return $asset;
    }

    protected function createDir(Asset $asset)
    {
        $dir = DIR_ROOT.'/'.$this->imagePath.'/'.$asset->getUser()->getFolderName();

        if (!is_dir($dir)){
            if (!mkdir($dir, 0755)) {
                throw new \Exception('Directory not create');
            }
        }

        return $dir;
    }

    protected function createFileName(Asset $asset)
    {
        return $this->createHash(uniqid(rand(),1)).'.'.explode('/', $asset->getUpload()['type'])[1];
    }

    protected function removeFile(Asset $asset)
    {
        if ($asset->getName())
        {
            $paths = [
                DIR_ROOT.'/'.$this->imagePath.'/'.$asset->getDirectory().'/'.$asset->getName(),
                DIR_ROOT.'/'.$this->imagePath.'/'.$asset->getDirectory().'/'.Asset::THUMB_PREFIX.$asset->getName()
            ];

            foreach($paths as $path)
            {
                if (is_file($path))
                {
                    unlink($path);
                }
            }
        }

    }

    public function assetTable()
    {
        return [
            [
                'type' => TableManager::TYPE_TABLE,
                'ajaxRoute' => [
                    'route' => 'admin-asset',
                    'parameters' => [],
                ],
                'tableId' => 'admin-list-all-patient',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_STRING,
                'name' => '#id',
                'percent' => '5%',
                'property' => 'id',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_FUNCTION,
                TableManager::TYPE_COLUMN_FUNCTION => function($idType, AssetManager $assetManager) {
                    return $assetManager->getTypesName($idType);
                },
                'name' => 'Type',
                'percent' => '20%',
                'property' => 'type',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_FUNCTION,
                TableManager::TYPE_COLUMN_FUNCTION => function($user, AssetManager $assetManager) {
                    return '#'.$user->getId().' '.$user->getFullName();
                },
                'name' => 'User name',
                'percent' => '25%',
                'property' => 'user',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_STRING,
                'name' => 'Title',
                'percent' => '15%',
                'property' => 'title',
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
                            'route' => 'admin-asset',
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
                            'route' => 'admin-asset',
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
//                              'title' => 'Title text',
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

    /**
     * @return \Imagine\Gd\Imagine
     */
    protected function getImagine()
    {
        if ($this->imagine === null) {
            $this->imagine = new \Imagine\Gd\Imagine();
        }
        return $this->imagine;
    }




}