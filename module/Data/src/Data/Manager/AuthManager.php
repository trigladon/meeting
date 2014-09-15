<?php

namespace Data\Manager;

use Data\Entity\User;
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
            // doctrine.authenticationservice.orm_default    Zend\Authentication\AuthenticationService
            $this->authService = $this->getServiceManager()->get('doctrine.authenticationservice.orm_default');
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
    }

    /**
     * @param $email
     * @param $password
     * @return \Zend\Authentication\Result
     */
    public function authentication($email, $password)
    {
        $this->getAuthService()->getAdapter()
            ->setIdentityValue($email)
            ->setCredentialValue($password);

        $result = $this->getAuthService()->authenticate();
        if ($result->isValid()){
            $this->getAuthService()->getStorage()->write($result->getIdentity());
        }

        return $result;
    }

}
