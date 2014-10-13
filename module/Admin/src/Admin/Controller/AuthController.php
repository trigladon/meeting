<?php

namespace Admin\Controller;

use Admin\Form\LoginForm;
use Admin\Form\RecoveryPasswordForm;
use Common\Manager\AuthManager;
use Common\Manager\TranslatorManager;
use Common\Manager\UserManager;
use Zend\Session\Container;
use Common\Manager\SessionManager;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class AuthController extends BaseController
{
	protected $defaultRoute = '';

    public function loginAction()
    {
        $authManager = new AuthManager($this->getServiceLocator());
        $translatorManager = new TranslatorManager($this->getServiceLocator());
	    $sessionContainer = new Container(SessionManager::SESSION_LOGIN_NAMESPACE);

	    $loginError = null;

	    try{
	        if ($authManager->hasIdentity()){
	            $this->setErrorMessage($translatorManager->translate('You are logged!'));
	            return $this->toHome();
	        }

            $loginForm = new LoginForm($this->getServiceLocator(), $sessionContainer);

	        $request = $this->getRequest();
	        if ($request->isPost()) {


	            $loginForm->setData($request->getPost());
	            if ($loginForm->isValid()) {

	                $data = $request->getPost();
	                $result = $authManager->authentication(
	                    $data['email'],
	                    $data['password'],
	                    (array_key_exists('remember', $data) ? true : false)
	                );

	                if ($result->isValid()){
		                if ($sessionContainer->offsetExists(SessionManager::SESSION_LOGIN_COUNT)){
			                $sessionContainer->offsetUnset(SessionManager::SESSION_LOGIN_COUNT);
		                }
	                    return $this->toHome();
	                } else {

		                if ($sessionContainer->offsetExists(SessionManager::SESSION_LOGIN_COUNT)){
			                $countTemp = $sessionContainer->offsetGet(SessionManager::SESSION_LOGIN_COUNT) + 1;
							$sessionContainer->offsetSet(SessionManager::SESSION_LOGIN_COUNT, $countTemp);
			                if ($countTemp >= 2) {
				                $loginForm->addCaptcha($translatorManager);
			                }
		                } else {
							$sessionContainer->offsetSet(SessionManager::SESSION_LOGIN_COUNT, 0);
		                }
		                $this->setErrorMessage($translatorManager->translate('Invalid email or password'));
	                }
	            }
	        }
	    }catch (\Exception $e){
            throw new \Exception($e->getMessage());
			//TODO view exception or redirect and log error
	    }

        return [
            'loginForm' => $loginForm,
	        'loginError' => $loginError,
        ];
    }

	public function recoveryPasswordAction()
	{
		$translatorManager = new TranslatorManager($this->getServiceLocator());
		$request = $this->getRequest();
		$recoveryForm = null;

		try{

			$recoveryForm = new RecoveryPasswordForm($this->getServiceLocator(), $translatorManager);

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
			//TODO view exception or redirect and log error
		}

		return [
			'recoveryForm' => $recoveryForm,
		];
	}


	public function refreshCaptchaAction()
	{
		if (!$this->getRequest()->isXmlHttpRequest()){
			$this->getResponse()->setStatusCode(404);
			return;
		}
		$sessionContainer = new Container(SessionManager::SESSION_LOGIN_NAMESPACE);
		$loginForm = new LoginForm($this->getServiceLocator(), $sessionContainer);
		$captcha = $loginForm->get('captcha')->getCaptcha();

		$data['id'] = $captcha->generate();
		$data['src'] = $captcha->getImgUrl() . $captcha->getId() . $captcha->getSuffix();
		return new JsonModel($data);
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

	public function generateCaptchaAction()
	{

		$response = $this->getResponse();
		$response->getHeaders()->addHeaderLine('Content-Type', "image/png");

		$id = $this->params('id', false);

		if ($id) {

			$image = './data/captcha/' . $id;

			if (file_exists($image) !== false) {
				$imagegetcontent = @file_get_contents($image);

				$response->setStatusCode(200);
				$response->setContent($imagegetcontent);

				if (file_exists($image) == true) {
					unlink($image);
				}
			}

		}

		return $response;

	}

}