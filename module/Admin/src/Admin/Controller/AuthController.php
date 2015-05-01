<?php

namespace Admin\Controller;

use Admin\Form\LoginForm;
use Admin\Form\RecoveryPasswordForm;
use Common\Manager\AuthManager;
use Common\Manager\TranslatorManager;
use Common\Manager\UserManager;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class AuthController extends BaseController
{
	protected $defaultRoute = '';

    public function loginAction()
    {
        $authManager = new AuthManager($this->getServiceLocator());
        $translatorManager = new TranslatorManager($this->getServiceLocator());
        $errorMessage = null;
        $loginForm = null;

	    try{
	        if ($authManager->hasIdentity()){
	            $this->setErrorMessage($translatorManager->translate('You are logged!'));
	            return $this->toHome();
	        }

            $loginForm = new LoginForm($this->getServiceLocator());

	        $request = $this->getRequest();
	        if ($request->isPost()) {

	            $loginForm->setData($request->getPost());
	            if ($loginForm->isValid()) {

	                $data = $request->getPost();
	                $result = $authManager->authentication($data['email'], $data['password'], (isset($data['remember']) ? true : false));

	                if ($result->isValid()){
                        $loginForm->unsetSessionLoginCount();
						if ($this->isGranted('Admin')) {
							return $this->toHome();
						} else {
							return $this->toRoute('home');
						}

	                } else {
                        $errorMessage = $translatorManager->translate($authManager->getAuthenticationMessage($result));
		                $loginForm->updateSessionLoginContainer();
		                $this->setErrorMessage($translatorManager->translate($authManager->getAuthenticationMessage($result)));
	                }
	            }else{
					$loginForm->updateSessionLoginContainer();
				}
	        }
	    }catch (\Exception $e){
//            throw new \Exception($e->getMessage());
	    }
		$viewModel = new ViewModel([
			'loginForm' => $loginForm,
            'errorMessage' => $errorMessage,
		]);
        $viewModel->setTemplate('admin/auth/login');
        return $viewModel;
    }

	public function recoveryPasswordAction()
	{
		$translatorManager = new TranslatorManager($this->getServiceLocator());
		$request = $this->getRequest();
		$recoveryForm = null;

		try{

			$recoveryForm = new RecoveryPasswordForm($this->getServiceLocator());

			if ($request->isPost())
			{
				$recoveryForm->setData($request->getPost());

				if ($recoveryForm->isValid()) {

                    $userManager = new UserManager($this->getServiceLocator());
                    if ($userManager->resetPassword($recoveryForm->getData()))
                    {
                        $view = new ViewModel();
                        return $view->setTemplate('admin/auth/recovery-password-send');
                    } else {
                        $this->setErrorMessage($translatorManager->translate("Error to send message. Please connect to administrator."));
                    }
				}
			}

		}catch (\Exception $e){
            throw new \Exception($e->getMessage());
		}

		$viewModel = new ViewModel([
			'recoveryForm' => $recoveryForm,
		]);

		return $viewModel->setTemplate('admin/auth/recovery-password');
	}


	public function refreshCaptchaAction()
	{
		if (!$this->getRequest()->isXmlHttpRequest()){
			return $this->getResponse()->setStatusCode(404);
		}

		$loginForm = new LoginForm($this->getServiceLocator());
		$captcha = $loginForm->get('captcha')->getCaptcha();

		return new JsonModel([
            'id' => $captcha->generate(),
            'src' => $captcha->getImgUrl() . $captcha->getId() . $captcha->getSuffix(),
        ]);
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

        return $this->toRoute('admin-login');
    }

}