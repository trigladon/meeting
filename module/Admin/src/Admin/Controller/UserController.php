<?php

namespace Admin\Controller;

use Admin\Form\NewUserForm;
use Admin\Form\UserForm;
use Common\Entity\User;
use Common\Manager\TableManager;
use Common\Manager\UserManager;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Form\View\Helper\Form;

class UserController extends BaseController {

    protected $defaultRoute = 'admin-user';

    public function allAction()
    {
        $userManager = new UserManager($this->getServiceLocator());
        $tableManager = new TableManager($this->getServiceLocator(), $userManager);
        $tableManager->setColumnsList($userManager->getUserTableInfo());

        if ($this->getRequest()->isXmlHttpRequest()) {

            return new JsonModel(
                $this->getAjaxTableList($userManager, $tableManager, 'getUserListDataForTable')
            );
        }

        $view = new ViewModel([
            'tableInfo' => $tableManager->getTableInfo(),
            'url' => [
                'route' => 'admin-user',
                'parameters' => ['action' => 'add']
            ]
        ]);

        return $view->setTemplate('/common/all-page');
    }

    public function addAction()
    {

        try{
            /**
             * @var $userForm UserForm
             */
            $userForm =  $this->getServiceLocator()->get('FormElementManager')->get('Admin\Form\UserForm');

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
            'template' => '/admin/user/_userForm.phtml',
            'parameters' => ['userForm' => $userForm]
        ]);

        return $view->setTemplate('/common/add-edit-page');
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
            $userForm = $this->getServiceLocator()->get('FormElementManager')->get('Admin\Form\UserForm');
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

        $view = new ViewModel([
            'template' => '/admin/user/_userForm.phtml',
            'parameters' => ['userForm' => $userForm]
        ]);

        return $view->setTemplate('/common/add-edit-page');
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
            $this->setSuccessMessage($userManager->getTranslatorManager()->translate('Account remove success'));
            return $this->toDefaultRoute();

        }catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }


    }




}