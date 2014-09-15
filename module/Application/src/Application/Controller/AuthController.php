<?php

namespace Application\Controller;

use Application\Form\LoginForm;
use Data\Manager\AuthManager;

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

                $data = $request->getPost();
                $authManager = new AuthManager($this->getServiceLocator());
                $result = $authManager->authentication($data['email'], $data['password']);

                if ($result->isValid()){
                    return $this->toHome();
                } else {
                    $this->setErrorMessage('Invalid email or password');
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
        $authManager->logout();
        return $this->toHome();
    }

}