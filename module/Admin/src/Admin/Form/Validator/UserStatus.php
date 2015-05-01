<?php

namespace Admin\Form\Validator;

use DoctrineModule\Validator\ObjectExists;
use Common\Entity\User;

class UserStatus extends ObjectExists
{

    const ERROR_STATUS_BANNED = 'userBanned';
    const ERROR_STATUS_NO_ACTIVE = 'userNotActive';
    const ERROR_USER_NOT_FOUND = "userNotFound";
    const ERROR_STATUS_NOT_CONFIRMED_REGISTRATION = 'notConfirmedRegistration';

    protected $messageTemplates = array(
            self::ERROR_STATUS_BANNED    => 'You are banned',
            self::ERROR_STATUS_NO_ACTIVE => "Your account is not active",
            self::ERROR_USER_NOT_FOUND   => "Account not found",
            self::ERROR_STATUS_NOT_CONFIRMED_REGISTRATION => 'You are not confirmed registration',
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
        $match = $this->objectRepository->findOneBy($value);
        if ($match === null || $match->getDeleted() === User::DELETED_YES) {
            $this->error(self::ERROR_USER_NOT_FOUND);
            return false;
        }

        $error = null;
        switch($match->getStatus())
        {
            case User::STATUS_NOT_CONFIRMED_REGISTRATION: $error = self::ERROR_STATUS_NOT_CONFIRMED_REGISTRATION; break;
            case User::STATUS_BANNED: $error = self::ERROR_STATUS_BANNED; break;
            case User::STATUS_NO_ACTIVE: $error = self::ERROR_STATUS_NO_ACTIVE; break;
        }

        if ($error !== null) {
            $this->error($error);
            return false;
        }

        return true;
    }

}