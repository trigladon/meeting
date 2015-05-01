<?php

namespace Admin\Controller;

use Admin\Form\UserForm;
use Common\Manager\UserManager;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class UserController extends BaseController {

    public function allAction()
    {
        $userManager = new UserManager($this->getServiceLocator());
        $tableManager = new \Common\Manager\TableManager($this->getServiceLocator(), $userManager);
        $tableManager->setColumnsList($userManager->getUserTableInfo());

        if ($this->getRequest()->isXmlHttpRequest()) {

            return new JsonModel(
                $this->getAjaxTableList($userManager, $tableManager, 'getUserListDataForTable')
            );
        }

        $view = new ViewModel([
            'tableInfo' => $tableManager->getTableInfo(),
            'url' => [
                'route' => 'admin/default',
                'parameters' => ["controller" => "user", 'action' => 'add']
            ]
        ]);

        return $view->setTemplate('common/all-page');
    }

    public function addAction()
    {

        try{
            /**
             * @var $userForm UserForm
             */
            $userForm =  $this->getForm('Admin\Form\UserForm');

            $request = $this->getRequest();
            if ($request->isPost()) {

                $userForm->setData($request->getPost());

                if ($userForm->isValid()) {

                    $userManager = new UserManager($this->getServiceLocator());
                    $userManager->saveUser($userForm->getObject());
                    $this->setSuccessMessage($userManager->getTranslatorManager()->translate('New account create success'));
                    return $this->toDefaultRoute();
                }
            }

        }catch (\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }

        $view = new ViewModel([
            'template' => 'admin/user/_userForm',
            'parameters' => ['userForm' => $userForm]
        ]);

        return $view->setTemplate('common/add-edit-page');
    }

    public function editAction()
    {

        try{
            $userManager = new UserManager($this->getServiceLocator());
            $user = $userManager->getDAO()->findById((int)$this->params()->fromRoute('id', 0));

            if ($user === null) {
                $this->setErrorMessage('Account not found');
                return $this->toDefaultRoute();
            }

            $request = $this->getRequest();
            /**
             * @var $userForm UserForm
             */
            $userForm = $this->getForm('Admin\Form\UserForm')->bind($user);

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

        $view = new ViewModel([
            'template' => 'admin/user/_userForm',
            'parameters' => ['userForm' => $userForm]
        ]);

        return $view->setTemplate('common/add-edit-page');
    }

    public function deleteAction()
    {
        try{

            $userManager = new UserManager($this->getServiceLocator());
            $user = $userManager->getDAO()->findById((int)$this->params()->fromRoute('id', 0));
            if ($user === null) {
                $this->setErrorMessage('Account not found');
                return $this->toDefaultRoute();
            }

            $userManager->removeUser($user);

        }catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        $this->setSuccessMessage($userManager->getTranslatorManager()->translate('Account remove success'));
        return $this->toDefaultRoute();
    }




}