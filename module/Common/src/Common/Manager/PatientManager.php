<?php

namespace Common\Manager;

use Common\DAO\PatientDAO;
use Common\DAO\UserDAO;
use Common\Entity\Asset;
use Common\Entity\Patient;
use Common\Entity\User;

class PatientManager extends BaseEntityManager
{

    protected $dao = null;

    public function getDAO()
    {
        if ($this->dao === null) {
            $this->dao = new PatientDAO($this->getServiceLocator());
        }

        return $this->dao;
    }

    public function getUserFullName(User $user)
    {
        return $user->getFullName();
    }

    public function getCheckedNameForSelect()
    {
        return [
            Patient::CHECK_NO => $this->getTranslatorManager()->translate('No'),
            Patient::CHECK_YES => $this->getTranslatorManager()->translate('Yes'),
        ];
    }

    public function getCheckedNameByIdCheck($idCheck)
    {
        $selectData = $this->getCheckedNameForSelect();

        if (isset($selectData[$idCheck])) {
            return $selectData[$idCheck];
        }
        return $idCheck;
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
            Patient::PUBLISHED,
            Patient::UNPUBLISHED,
        ];
    }


    public function savePatient(Patient $patient)
    {
        $assets = $patient->getAssets();
        $assetManager = new AssetManager($this->getServiceLocator());


        // TODO need refactoring
        /** @var $asset Asset */
        foreach($assets as $asset){
            if (!$asset->getUser()){
                $asset->setUser($patient->getUser());
            }
        }

        foreach($assets as $asset){
            $assetManager->saveAsset($asset);
        }

        $patient->setCheck(($patient->getCheck() === '0' ? 'no' : 'yes'));
        $patient->setPublished(($patient->getPublished() === '0' ? 'no' : 'yes'));
        $assetManager->saveAsset($patient->getImage()->setUser($patient->getUser()));

        $this->getDAO()->save($patient);
    }

    public function patientTable()
    {
        return [
            [
                'type' => TableManager::TYPE_TABLE,
                'ajaxRoute' => [
                    'route' => 'admin-patient',
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
                'type' => TableManager::TYPE_COLUMN_STRING,
                'name' => 'Title',
                'percent' => '35%',
                'property' => 'title',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_FUNCTION,
                TableManager::TYPE_COLUMN_FUNCTION => function($user, PatientManager $patientManager) {
                    return $patientManager->getUserFullName($user);
                },
                'name' => 'User name',
                'percent' => '15%',
                'property' => 'user',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_FUNCTION,
                TableManager::TYPE_COLUMN_FUNCTION => function($published, PatientManager $patientManager){
                    return $patientManager->getPublishedName($published);
                },
                'name' => 'Published',
                'percent' => '5%',
                'property' => 'published',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_FUNCTION,
                TableManager::TYPE_COLUMN_FUNCTION => function($idChecked, PatientManager $patientManager) {
                    return $patientManager->getCheckedNameByIdCheck($idChecked);
                },
                'name' => 'Checked',
                'percent' => '5%',
                'property' => 'check',
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
                            'route' => 'admin-patient',
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
                            'route' => 'admin-patient',
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
                'percent' => '15%',
                'property' => 'actions',
            ],
        ];
    }

}