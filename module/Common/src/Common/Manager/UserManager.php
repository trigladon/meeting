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

    public function getUserTypesForSelect()
    {
        return [
            User::TYPE_COMPANY => $this->getTranslatorManager()->translate('Company'),
            User::TYPE_USER => $this->getTranslatorManager()->translate('User'),
        ];
    }

    public function getUserTypeNameByIdType($idType)
    {
        if (isset($this->getUserTypesForSelect()[$idType])) {
            return $this->getUserTypesForSelect()[$idType];
        }
        return $idType;
    }

    public function getUserStatusForSelect()
    {
        return [
            User::STATUS_ACTIVE => $this->getTranslatorManager()->translate('Active'),
            User::STATUS_NO_ACTIVE => $this->getTranslatorManager()->translate('No active'),
            User::STATUS_BANNED => $this->getTranslatorManager()->translate('Banned'),
        ];
    }

    public function getUserStatusNameByIdStatus($idStatus)
    {
        if (isset($this->getUserStatusForSelect()[$idStatus])) {
            return $this->getUserStatusForSelect()[$idStatus];
        }

        return $idStatus;
    }

    public function getListDataForTable($offset, $limit)
    {
        return [
            'count' => $this->getDAO()->countAll(),
            'data' => $this->getDAO()->findAllOffsetAndLimit($offset*$limit, $limit)
        ];
    }

}