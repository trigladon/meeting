<?php

namespace Common\Manager;

use Common\Entity\User;
use Zend\Authentication\Result;
use Zend\Crypt\Password\Bcrypt;

class AuthManager extends BaseManager
{

    protected $authService = null;

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
        $bCrypt = new Bcrypt();
        return $bCrypt->verify($password.$identity->getSalt(), $identity->getPassword());
    }

    /**
     *logout
     */
    public function logout() {
        $this->getAuthService()->clearIdentity();
        $userSession = new \Zend\Session\SessionManager();
        $userSession->forgetMe();
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
            $this->getAuthService()->getStorage()->write($result->getIdentity());

            if ($remember) {
                $time = 60*60*24*365;

                $sessionManager = new \Zend\Session\SessionManager();
                $sessionManager->rememberMe($time);
            }
        }

        return $result;
    }

    public function result($result)
    {

        switch ($result->getCode()) {
            case Result::SUCCESS:{}; break;
            case Result::FAILURE: {};
            case Result::FAILURE_CREDENTIAL_INVALID: {};
            case Result::FAILURE_IDENTITY_NOT_FOUND: {};
            case Result::FAILURE_IDENTITY_AMBIGUOUS: {};
            case Result::FAILURE_UNCATEGORIZED: {} ;break;
            default: {}; break;
        }


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
