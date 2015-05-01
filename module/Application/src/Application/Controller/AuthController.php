<?php

namespace Application\Controller;


use Admin\Controller\AuthController as AuthAdminController;
use Common\Manager\UserManager;
use Zend\View\Model\ViewModel;


class AuthController extends AuthAdminController
{

    protected $defaultRoute = '';

    public function loginAction()
    {
        $view = parent::loginAction();
        if ($view instanceof ViewModel){
            return $view->setTemplate('application/auth/login');
        }
        return $view;
    }

    public function logoutAction()
    {
        $response = parent::logoutAction();
        return $this->toRoute('home');
    }

    public function registrationAction()
    {
        /** @var $registrationForm \Application\Form\RegistrationForm */
        $registrationForm = $this->getForm('Application\Form\RegistrationForm');
        $request = $this->getRequest();

        if ($request->isPost())
        {
            $registrationForm->setData($request->getPost());
            try{
                if ($registrationForm->isValid()){

                    $userManager = new UserManager($this->getServiceLocator());
                    $userManager->registeredUser($registrationForm->getObject(), $this->getLocale());
                    $this->setSuccessMessage($this->translate('Registration success'));
                    return $this->toRoute('sign-up-success');
                }
            }catch (\Exception $e){}
        }

        $view = new ViewModel([
            'form' => $registrationForm,
        ]);
        return $view->setTemplate('application/auth/registration');
    }

    public function registrationSuccessAction()
    {
        $view = new ViewModel();
        return $view->setTemplate('application/auth/registration-success');
    }

    public function activationAction()
    {
        $code = $this->params()->fromRoute('code', null);
        $userManager = new UserManager($this->getServiceLocator());
        try{
            $user = $userManager->getDAO()->findByCode($code);
            if ($user){
                $userManager->userActivate($user);

                $authManager = new \Common\Manager\AuthManager($this->getServiceLocator());
                if (!$authManager->hasIdentity()){
                    $authManager->getAuthService()->getStorage()->write($user);
                }

                $this->setSuccessMessage($this->translate('Registration success'));
                return $this->toRoute('home');
            }
        }catch (\Exception $e){}
        return $this->notFoundAction();
    }

}