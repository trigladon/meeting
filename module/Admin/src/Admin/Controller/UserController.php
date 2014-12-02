<?php

namespace Admin\Controller;

use Admin\Form\NewUserForm;
use Admin\Form\UserForm;
use Common\Entity\User;
use Common\Manager\CommonManager;
use Common\Manager\UserManager;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class UserController extends BaseController {

    protected $defaultRoute = 'admin-user';

    public function allAction() {

        $userManager = new UserManager($this->getServiceLocator());
        $commonManager = new CommonManager($this->getServiceLocator(), $userManager);
        $commonManager->setColumnsList($this->getUserTableInfo());

        if ($this->getRequest()->isXmlHttpRequest()) {

            try{

                $page = (int)$this->getRequest()->getPost('start');
                $limit = (int)$this->getRequest()->getPost('length');

                $data = $userManager->getListDataForTable($page, $limit);

                $result = $commonManager->getDataContent($data['data']);
                $result['recordsTotal'] = $data['count'];
                $result['recordsFiltered'] = $data['count'];

            }catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }

            return new JsonModel($result);
        }

        $tableInfo = $commonManager->getTableInfo();

        return ['tableInfo' => $tableInfo];
    }

    public function addAction()
    {

        try{

            $userForm = new UserForm($this->getServiceLocator());
            $user = new User();
            $userForm->bind($user);

            $request = $this->getRequest();
            if ($request->isPost()) {

                $userForm->setData($request->getPost());

                if ($userForm->isValid()) {

                    $userManager = new UserManager($this->getServiceLocator());
                    $userManager->saveUser($user);
                    $this->setSuccessMessage($userManager->getTranslatorManager()->translate('New account create success'));
                    return $this->toDefaultRoute();
                }
            }

        }catch (\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }


        return [
            'userForm' => $userForm
        ];
    }

    public function editAction()
    {

        try{
            $userManager = new UserManager($this->getServiceLocator());
            $user = $userManager->getDAO()->findById($this->params()->fromRoute('id', 0));
            if ($user === null) {
                $this->setErrorMessage('Account not found');
                return $this->toDefaultRoute();
            }
            $request = $this->getRequest();
            $userForm = new UserForm($this->getServiceLocator(), $userManager);
            $userForm->bind($user);

            if ($request->isPost()) {

                $userForm->setData($request->getPost());

                if ($userForm->isValid()) {

                    $userManager->saveUser($user, false);
                    $this->setSuccessMessage($userManager->getTranslatorManager()->translate('Account save success'));
                    return $this->toDefaultRoute();
                }
            }

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return new ViewModel(
            ['userForm' => $userForm],
            ['template' => '/admin/user/add.phtml']
        );
    }

    public function deleteAction()
    {
        try{

            $userManager = new UserManager($this->getServiceLocator());
            $user = $userManager->getDAO()->findById($this->params()->fromRoute('id', 0));
            if ($user === null) {
                $this->setErrorMessage('Account not found');
                return $this->toDefaultRoute();
            }

            $userManager->removeUser($user);
            $this->setSuccessMessage($userManager->getTranslatorManager()->translate('Account remove success'));
            return $this->toDefaultRoute();

        }catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }


    }

    protected function getUserTableInfo()
    {
        return [
            [
                'type' => CommonManager::TYPE_TABLE,
                'ajaxRoute' => [
                    'route' => 'admin-user',
                    'parameters' => [],
                ],
                'tableId' => 'admin-list-all-users',
            ],
            [
                'type' => CommonManager::TYPE_COLUMN_STRING,
                'name' => '#id',
                'percent' => '5%',
                'property' => 'id',
            ],
            [
                'type' => CommonManager::TYPE_COLUMN_FUNCTION,
                CommonManager::TYPE_COLUMN_FUNCTION => 'getFullName',
                'name' => 'Full name',
                'percent' => '40%',
                'property' => 'fullName',
            ],
            [
                'type' => CommonManager::TYPE_COLUMN_FUNCTION,
                CommonManager::TYPE_COLUMN_FUNCTION => function($idType, UserManager $userManager) {
                    return $userManager->getTypeNameByIdType($idType);
                },
                'name' => 'Type',
                'percent' => '10%',
                'property' => 'type'
            ],
            [
                'type' => CommonManager::TYPE_COLUMN_FUNCTION,
                CommonManager::TYPE_COLUMN_FUNCTION => function($idStatus, UserManager $userManager) {
                    return $userManager->getStatusNameByIdStatus($idStatus);
                },
                'name' => 'Status',
                'percent' => '10%',
                'property' => 'status',
            ],
            [
                'type' => CommonManager::TYPE_COLUMN_FUNCTION,
                CommonManager::TYPE_COLUMN_FUNCTION => function($idDeleted, UserManager $userManager) {
                    return $userManager->getDeletedNameByIdDeleted($idDeleted);
                },
                'name' => 'Deleted',
                'percent' => '5%',
                'property' => 'deleted',
            ],
            [
                'type' => CommonManager::TYPE_COLUMN_DATETIME,
                'name' => 'Created',
                'percent' => '15%',
                'property' => 'created',
            ],
            [
                'type' => CommonManager::TYPE_COLUMN_BUTTON,
                CommonManager::TYPE_COLUMN_BUTTON => [
                    [
                        'url' => [
                            'route' => 'admin-user',
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
                        'type' => CommonManager::BUTTON_TYPE_DEFAULT,
                        CommonManager::BUTTON_TYPE_DEFAULT => 'edit',
                    ],
//                    [
//                        'type' => CommonManager::BUTTON_TYPE_FROM_VALUE,
//                        'property' => 'id',
//                        CommonManager::BUTTON_TYPE_FROM_VALUE => [
//                            0 => [
//                                'url' => [
//                                    'route' => 'admin-user',
//                                    'parameters' => [
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
//                                'type' => CommonManager::BUTTON_TYPE_DEFAULT,
//                                CommonManager::BUTTON_TYPE_DEFAULT => 'Publish',
//                            ],
//                            1 => [
//                                'url' => [
//                                    'route' => 'admin-user',
//                                    'parameters' => [
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
//                                'type' => CommonManager::BUTTON_TYPE_DEFAULT,
//                                CommonManager::BUTTON_TYPE_DEFAULT => 'Unpublish',
//                            ],
//                        ],
//                    ],
                    [
                        'url' => [
                            'route' => 'admin-user',
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
//                            'title' => 'Title text',
                            'description' => 'Description "" \"" text >text </span>',
                            'button' => 'Remove',
                            'color' => 'btn btn-danger',
                        ],
                        'name' => 'Remove',
                        'type' => CommonManager::BUTTON_TYPE_DEFAULT,
                        CommonManager::BUTTON_TYPE_DEFAULT => 'delete',
                    ],

                ],
                'name' => 'Actions',
                'percent' => '15%',
                'property' => 'actions',
            ],
        ];
    }


}