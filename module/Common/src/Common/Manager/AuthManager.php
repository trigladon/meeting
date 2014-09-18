<?php

namespace Common\Manager;

use Common\Entity\User;
use Zend\Crypt\Password\Bcrypt;
use Zend\Session\SessionManager;

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
        $userSession = new SessionManager();
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

                $sessionManager = new SessionManager();
                $sessionManager->rememberMe($time);
            }
        }

        return $result;
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
