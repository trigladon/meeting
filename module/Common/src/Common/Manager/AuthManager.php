<?php

namespace Common\Manager;

use Common\Entity\User;
use Zend\Authentication\Result;
use Zend\Crypt\Password\Bcrypt;
use Zend\Authentication\AuthenticationService;

class AuthManager extends BaseManager
{

    protected $authService = null;

    protected $cookieTime = 31536000; //60*60*24*365

    /**
     * @return \Zend\Authentication\AuthenticationService
     */
    public function getAuthService()
    {
        if ($this->authService === null) {
            $this->authService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
        }

        return $this->authService;
    }

    /**
     * @param User $identity
     * @param $password
     * @return bool
     */
    public static function credentialCallable(User $identity, $password)
    {
        $bCrypt = new Bcrypt(['salt' => $identity->getSalt()]);
        return $bCrypt->verify($password, $identity->getPassword());
    }

    /**
     *logout
     */
    public function logout()
    {
        $this->getAuthService()->clearIdentity();
        $sessionManager = $this->getServiceLocator()->get('Zend\Session\SessionManager');;
        $sessionManager->forgetMe();
    }

    /**
     * @param $email
     * @param $password
     * @param $remember
     * @return \Zend\Authentication\Result
     */
    public function authentication($email, $password, $remember)
    {
        $this->getAuthService()->getAdapter()
            ->setIdentityValue($email)
            ->setCredentialValue($password);

        $result = $this->getAuthService()->authenticate();
        if ($result->isValid()){

            if ($remember) {
                $sessionManager = $this->getServiceLocator()->get('Zend\Session\SessionManager');
                $sessionManager->rememberMe($this->cookieTime);
            }
        }

        return $result;
    }

    /**
     * @param Result $result
     *
     * @return string
     */
    public function getAuthenticationMessage(Result $result)
    {
        $message = 'Login failed';

        switch ($result->getCode()) {
            case Result::SUCCESS:{ $message = 'You are logged successfully.'; }; break;
            case Result::FAILURE_CREDENTIAL_INVALID: { $message = 'Login failed. Your credential is invalid.'; } break;
            case Result::FAILURE_IDENTITY_NOT_FOUND: { $message = 'Login failed. Account not found.'; } break;
            case Result::FAILURE: ;
            case Result::FAILURE_IDENTITY_AMBIGUOUS: ;
            case Result::FAILURE_UNCATEGORIZED: { $message = 'Login failed.'; } break;
        }

        return $message;
    }

    public function getIdentity()
    {
        return $this->getAuthService()->getIdentity();
    }

    public function hasIdentity()
    {
        return $this->getAuthService()->hasIdentity();
    }

}
