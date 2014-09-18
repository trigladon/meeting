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
        $loginForm = new LoginForm();
        $request = $this->getRequest();

        if ($request->isPost()) {

            $loginForm->setData($request->getPost());

            if ($loginForm->isValid()) {

                $translatorManager = new TranslatorManager($this->getServiceLocator());
                $authManager = new AuthManager($this->getServiceLocator());

                $data = $request->getPost();
                $result = $authManager->authentication($data['email'], $data['password'], (array_key_exists('remember', $data) ? true : false));

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
        $authManager->logout();
        $this->setSuccessMessage($translatorManager->translate('Success logout'));


        return $this->toHome();
    }

}