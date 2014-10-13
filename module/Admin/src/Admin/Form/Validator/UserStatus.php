<?php

namespace Admin\Form\Validator;

use DoctrineModule\Validator\ObjectExists;
use Common\Entity\User;

class UserStatus extends ObjectExists
{

    const ERROR_STATUS_BANNED = 'userBanned';
    const ERROR_STATUS_NO_ACTIVE = 'userNotActive';
    const ERROR_USER_NOT_FOUND = "userNotFound";

    protected $messageTemplates = array(
            self::ERROR_STATUS_BANNED    => 'You are banned',
            self::ERROR_STATUS_NO_ACTIVE => "Your account is not active",
            self::ERROR_USER_NOT_FOUND   => "Account not found",
        );

    protected $token;

    /**
     * @param mixed $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    public function __construct($token)
    {
        $this->setToken($token);
        parent::__construct(is_array($token) ? $token : null);
    }

    public function isValid($value, array $context = null)
    {
        $value = $this->cleanSearchValue($value);
        /** @var $match \Common\Entity\User */
        $match      = $this->objectRepository->findOneBy($value);
        if ($match === null) {
            $this->error(self::ERROR_USER_NOT_FOUND);
            return;
        }

        $userStatus = $match->getStatus();
        if ($userStatus === User::STATUS_BANNED) {
            $this->error(self::ERROR_STATUS_BANNED);
            return false;
        }

        if ($userStatus === User::STATUS_NO_ACTIVE) {
            $this->error(self::ERROR_STATUS_NO_ACTIVE);
            return false;
        }

        return true;
    }

}