<?php

namespace Common\Manager;

use Common\DAO\LanguageDAO;
use Common\DAO\UserCodeDAO;
use Common\DAO\UserDAO;
use Common\DAO\UserRoleDAO;
use Common\Entity\Role;
use Common\Entity\User;
use Common\Entity\UserCode;
use Zend\Crypt\Password\Bcrypt;
use Zend\Math\Rand;

class UserManager extends BaseEntityManager
{

    protected $userDAO = null;

    protected $userCodeDAO = null;

    protected $userRoleDAO = null;

    /**
     * @return UserDAO|null
     */
    public function getDAO()
    {
        if ($this->userDAO === null) {
            $this->userDAO = new UserDAO($this->getServiceLocator());
        }

        return $this->userDAO;
    }

    /**
     * @return UserCodeDAO|null
     */
    public function getDAOUserCode()
    {
        if ($this->userCodeDAO === null) {
            $this->userCodeDAO = new UserCodeDAO($this->getServiceLocator());
        }

        return $this->userCodeDAO;
    }

    /**
     * @return UserRoleDAO|null
     */
    public function getDAOUserRole()
    {
        if ($this->userRoleDAO === null) {
            $this->userRoleDAO = new UserRoleDAO($this->getServiceLocator());
        }

        return $this->userRoleDAO;
    }


    public function resetPassword($data)
    {
        $user = $this->getDAO()->findByEmail($data['email']);

        if ($user === null) {
            return false;
        }

        $mailManager = new MailManager($this->getServiceLocator());

        $code = $this->getDAOUserCode()->findByUserAndType($user, UserCode::TYPE_RECOVERY);
        $userCode = $this->createUserCode($user, UserCode::TYPE_RECOVERY, $code);
        $this->getDAOUserCode()->save($userCode);

        $mailManager->sendEmailContent($user, 'Subject', '<h1>content</h1>');

        return true;
    }

    protected function createUserCode(User $user, $type, $code = '')
    {
        if (!($code instanceof UserCode))
        {
            $code = new UserCode();
        }

        $code->setType($type);
        $code->setCode(md5($user->getEmail().time()));

        return $code;
    }

    public function removeUser(User $user)
    {
        //TODO set delete flag for patient if exist
        $user->setDeleted(User::DELETED_YES);
        $this->saveUser($user, false);
    }

    public function getTypesForSelect()
    {
        return [
            User::TYPE_COMPANY => $this->getTranslatorManager()->translate('Company'),
            User::TYPE_USER => $this->getTranslatorManager()->translate('User'),
        ];
    }

    /**
     * @param $offset
     * @param $limit
     *
     * @return array
     */
    public function getUserListDataForTable($offset, $limit)
    {
        return [
            'count' => $this->getDAO()->countAll(),
            'data' => $this->getDAO()->findAllJoinOffsetAndLimit($offset, $limit)
        ];
    }

    public function getTypeNameByIdType($idType)
    {
        $selectData = $this->getTypesForSelect();

        if (isset($selectData[$idType])) {
            return $selectData[$idType];
        }
        return $idType;
    }

    public function getStatusForSelect()
    {
        return [
            User::STATUS_NOT_CONFIRMED_REGISTRATION => $this->getTranslatorManager()->translate('Send e-mail to confirm'),
            User::STATUS_ACTIVE => $this->getTranslatorManager()->translate('Active'),
            User::STATUS_NO_ACTIVE => $this->getTranslatorManager()->translate('No active'),
            User::STATUS_BANNED => $this->getTranslatorManager()->translate('Banned'),
        ];
    }

    public function getStatusNameByIdStatus($idStatus)
    {
        $selectData = $this->getStatusForSelect();

        if (isset($selectData[$idStatus])) {
            return $selectData[$idStatus];
        }

        return $idStatus;
    }

    public function saveUser(User $user, $createCode = true)
    {
        if ($this->isNewEntity($user)) {

            $salt = Rand::getString(Bcrypt::MIN_SALT_SIZE);
            $secure = new Bcrypt(['salt' => $salt]);
            $password = $this->generatePassword();
            $languageDAO = new LanguageDAO($this->getServiceLocator());

            $user->setSalt($salt)
                ->setPassword($secure->create($password))
                ->setLanguage($languageDAO->findById($this->getServiceLocator()->get('config')['projectData']['defaultLanguageId']));
        }

        if ($createCode || $user->getStatus() === User::STATUS_NOT_CONFIRMED_REGISTRATION || ($createCode && $user->getStatus() === User::STATUS_NOT_CONFIRMED_REGISTRATION)) {
            $user->setCode($this->createUserCode($user, UserCode::TYPE_REGISTRATION, $user->getCode()));
            // TODO send e-mail;
        }

        $this->getDAO()->save($user);

    }

    public function registeredUser(User $user, $locale=null)
    {
        $languageDAO = new LanguageDAO($this->getServiceLocator());

        $salt = Rand::getString(Bcrypt::MIN_SALT_SIZE);
        $secure = new Bcrypt(['salt' => $salt]);

        if (!$locale){
            $language = $languageDAO->findById($this->getServiceLocator()->get('config')['projectData']['defaultLanguageId']);
        }else{
            $language = $languageDAO->findByLocale($locale);
            if ($language){
                $language = $languageDAO->findById($this->getServiceLocator()->get('config')['projectData']['defaultLanguageId']);
            }
        }

        $user->setSalt($salt)
            ->setPassword($secure->create($user->getPassword()))
            ->setLanguage($language)
            ->setType(User::TYPE_USER)
            ->setDeleted(User::DELETED_NO)
            ->setStatus(User::STATUS_NOT_CONFIRMED_REGISTRATION)
            ->addRoles($this->getDAOUserRole()->findById(Role::ROLE_USER_ID));

        $user->setCode($this->createUserCode($user, UserCode::TYPE_REGISTRATION, $user->getCode()));

        $mailManager = new MailManager($this->getServiceLocator());

        $mailManager->sendEmailLayout(
            $user->getEmail(),
            $this->translate('Registration in').' '.$mailManager->getProjectConfig()['siteName'],
            ['user' => $user],
            'mail/registration',
            true
        );

        $this->getDAO()->save($user);
    }

    public function userActivate(User $user)
    {
        $user->setStatus(User::STATUS_ACTIVE);
        $this->getDAO()->save($user, false);
        $this->getDAO()->remove($user->getCode());
    }



    protected function generatePassword($length = 8)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $count = mb_strlen($chars);

        for ($i = 0, $result = ''; $i < $length; $i++) {
            $index = rand(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }

        return $result;
    }

    public function getUserForAdminSelect()
    {
        $result = array();
        /** @var $user User */
        foreach($this->getDAO()->findAllJoinCode() as $user){
            $result[$user->getId()] = '#'.$user->getId().' '.$user->getFullName().' ('.$this->getDeletedNameByIdDeleted($user->getDeleted()).')';
        }

        return $result;
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
        return $selectData[$idDeleted];
    }

    public function getRolesForSelect()
    {
        return [
            1 => $this->getTranslatorManager()->translate(Role::ROLE_GUEST),
            2 => $this->getTranslatorManager()->translate(Role::ROLE_USER),
            //3 => $this->getTranslatorManager()->translate(Role::ROLE_MANAGER),
            //4 => $this->getTranslatorManager()->translate(Role::ROLE_ADMIN),
            5 => $this->getTranslatorManager()->translate(Role::ROLE_GOD_MODE)
        ];
    }

    public function getUserTableInfo()
    {
        return [
            [
                'type' => TableManager::TYPE_TABLE,
                'ajaxRoute' => [
                    'route' => 'admin/default',
                    'parameters' => ["controller"=>"user"],
                ],
                'tableId' => 'admin-list-all-users',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_STRING,
                'name' => '#id',
                'percent' => '5%',
                'property' => 'id',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_FUNCTION,
                TableManager::TYPE_COLUMN_FUNCTION => 'getFullName',
                'name' => 'Full name',
                'percent' => '40%',
                'property' => 'fullName',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_FUNCTION,
                TableManager::TYPE_COLUMN_FUNCTION => function($idType, UserManager $userManager) {
                    return $userManager->getTypeNameByIdType($idType);
                },
                'name' => 'Type',
                'percent' => '10%',
                'property' => 'type'
            ],
            [
                'type' => TableManager::TYPE_COLUMN_FUNCTION,
                TableManager::TYPE_COLUMN_FUNCTION => function($idStatus, UserManager $userManager) {
                    return $userManager->getStatusNameByIdStatus($idStatus);
                },
                'name' => 'Status',
                'percent' => '10%',
                'property' => 'status',
            ],
            [
                'type' => TableManager::TYPE_COLUMN_FUNCTION,
                TableManager::TYPE_COLUMN_FUNCTION => function($idDeleted, UserManager $userManager) {
                    return $userManager->getDeletedNameByIdDeleted($idDeleted);
                },
                'name' => 'Deleted',
                'percent' => '5%',
                'property' => 'deleted',
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
                            'route' => 'admin/default',
                            'parameters' => [
                                [
                                    "name" => "controller",
                                    "value" => "user",
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
                    //                    [
                    //                        'type' => TableManager::BUTTON_TYPE_FROM_VALUE,
                    //                        'property' => 'id',
                    //                        TableManager::BUTTON_TYPE_FROM_VALUE => [
                    //                            0 => [
                    //                                'url' => [
                    //                                    'route' => 'admin/default',
                    //                                    'parameters' => [
                    //                                        [
                    //                                            "name" => "controller",
                    //                                            "value" => "user",
                    //                                        ],
                    //                                        [
                    //                                            'name' => 'action',
                    //                                            'value' => 'delete',
                    //                                        ],
                    //                                        [
                    //                                            'name' => 'id',
                    //                                            'property' => 'id'
                    //                                        ]
                    //                                    ]
                    //                                ],
                    //                                'modal' => [
                    //                                    'title' => 'Title text',
                    //                                    'description' => 'Description',
                    //                                    'button' => 'Remove',
                    //                                ],
                    //                                'name' => 'Publish',
                    //                                'type' => TableManager::BUTTON_TYPE_DEFAULT,
                    //                                TableManager::BUTTON_TYPE_DEFAULT => 'Publish',
                    //                            ],
                    //                            1 => [
                    //                                'url' => [
                    //                                    'route' => 'admin/default',
                    //                                    'parameters' => [
                    //                                        [
                    //                                            "name" => "controller",
                    //                                            "value" => "user",
                    //                                        ],
                    //                                        [
                    //                                            'name' => 'action',
                    //                                            'value' => 'delete',
                    //                                        ],
                    //                                        [
                    //                                            'name' => 'id',
                    //                                            'property' => 'id'
                    //                                        ]
                    //                                    ]
                    //                                ],
                    //                                'modal' => [
                    //                                    'title' => 'Title text',
                    //                                    'description' => 'Unpublish this user',
                    //                                    'button' => 'Unpublish',
                    //                                    'color' => 'btn purple',
                    //                                ],
                    //                                'name' => 'Unpublish',
                    //                                'type' => TableManager::BUTTON_TYPE_DEFAULT,
                    //                                TableManager::BUTTON_TYPE_DEFAULT => 'Unpublish',
                    //                            ],
                    //                        ],
                    //                    ],
                    [
                        'url' => [
                            'route' => 'admin/default',
                            'parameters' => [
                                [
                                    "name" => "controller",
                                    "value" => "user",
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
//                            'title' => 'Title text',
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