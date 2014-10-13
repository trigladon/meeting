<?php

namespace Common\Manager;

use Common\DAO\UserCodeDAO;
use Common\DAO\UserDAO;
use Common\Entity\User;
use Common\Entity\UserCode;

class UserManager extends BaseEntityManager
{

    protected $userDAO = null;

    protected $userCodeDAO = null;

    /**
     * @return UserDAO|null
     */
    public function getDAO()
    {
        if ($this->userDAO === null) {
            $this->userDAO = new UserDAO($this->getServiceLocator());
        }

        return $this->userDAO;
    }

    public function getDAOUserCode()
    {
        if ($this->userCodeDAO === null) {
            $this->userCodeDAO = new UserCodeDAO($this->getServiceLocator());
        }

        return $this->userCodeDAO;
    }


    public function resetPassword($data)
    {
        $user = $this->getDAO()->findByEmail($data['email']);

        if ($user === null) {
            return false;
        }

        $mailManager = new MailManager($this->getServiceLocator());

        $code = $this->getDAOUserCode()->findByUserAndType($user, UserCode::TYPE_RECOVERY);
        $userCode = $this->createUserCode($user, UserCode::TYPE_RECOVERY, $code);
        $this->getDAOUserCode()->save($userCode);

        $mailManager->sendEmailContent($user, 'Subject', '<h1>content</h1>');

        return true;
    }

    protected function createUserCode(User $user, $type, $code = '')
    {
        if (!($code instanceof UserCode))
        {
            $code = new UserCode();
        }

        $code->setType($type);
        $code->setCode(md5($user->getEmail().time()));
        $code->setUser($user);

        return $code;
    }

}