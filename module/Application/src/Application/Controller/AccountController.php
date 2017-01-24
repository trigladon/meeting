<?php

namespace Application\Controller;

use Common\Manager\UserManager;
use Zend\View\Model\ViewModel;

class AccountController extends BaseController
{


    public function settingsAction()
    {
        $viewModel = new ViewModel();
        return $viewModel->setTemplate('application/account/settings');
    }

    public function settingsEditAction()
    {
        /** @var $form \Application\Form\ProfileForm */
        $form = $this->getForm('Application\Form\ProfileForm')->bind($this->identity());

        if ($this->getRequest()->isPost()){

            $form->setData($this->getRequest()->getPost());

            if ($form->isValid()){
                $userManager = new UserManager($this->getServiceLocator());
                $userManager->saveUser($form->getObject());
                $this->setSuccessMessage($this->translate('Settings has been saved'));
                return $this->toRoute('account-settings');
            }
        }

        $viewModel = new ViewModel([
            'form' => $form
        ]);
        return $viewModel->setTemplate('application/account/settings-edit');
    }

    public function notificationsAction()
    {
        $viewModel = new ViewModel();
        return $viewModel->setTemplate('application/account/notifications');
    }

    public function forPayAction()
    {
        $viewModel = new ViewModel();
        return $viewModel->setTemplate('application/account/payments/for-pay');
    }

    public function paymentsHistoryAction()
    {
        $viewModel = new ViewModel();
        return $viewModel->setTemplate('application/account/payments/history');
    }

    public function lotsCurrentAction()
    {
        $viewModel = new ViewModel();
        return $viewModel->setTemplate('application/account/lots/current');
    }

    public function lotsHistoryAction()
    {
        $viewModel = new ViewModel();
        return $viewModel->setTemplate('application/account/lots/history');
    }

}
