<?php

namespace Common\Authentication;

use Zend\Authentication\Result;

class AuthResult extends Result
{

    const FAILURE_ACCOUNT_WAS_DELETED = -5;

    const FAILURE_ACCOUNT_NOT_ACTIVE = -6;

    const FAILURE_ACCOUNT_BANNED = -7;

    const FAILURE_NOT_CONFIRM_REGISTRATION = -8;

}