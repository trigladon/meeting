<?php

namespace Application\Controller;

use Application\Form\LoginForm;
use Common\Manager\AuthManager;
use Common\Manager\TranslatorManager;

class AuthController extends BaseController
{

    protected $defaultRoute = '';

    public function loginAction()
    {
        $authManager = new AuthManager($this->getServiceLocator());
        $translatorManager = new TranslatorManager($this->getServiceLocator());

        if ($authManager->hasIdentity()){
            $this->setErrorMessage($translatorManager->translate('You are logged!'));
            return $this->toHome();
        }

        $loginForm = new LoginForm();
        $request = $this->getRequest();
        if ($request->isPost()) {

            $loginForm->setData($request->getPost());

            if ($loginForm->isValid()) {

                $data = $request->getPost();
                $result = $authManager->authentication(
                    $data['email'],
                    $data['password'],
                    (isset($data['remember']) ? true : false)
                );

                if ($result->isValid()){
                    return $this->toHome();
                } else {
                    $this->setErrorMessage($translatorManager->translate('Invalid email or password'));
                }
            }
        }

        return [
            'loginForm' => $loginForm
        ];
    }

    public function logoutAction()
    {
        $authManager = new AuthManager($this->getServiceLocator());
        $translatorManager = new TranslatorManager($this->getServiceLocator());
        if ($authManager->hasIdentity()){
            $authManager->logout();
            $this->setSuccessMessage($translatorManager->translate('Success logout'));
        } else {
            $this->setErrorMessage($translatorManager->translate('You are not logged'));
        }


        return $this->toHome();
    }

}