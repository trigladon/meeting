<?php

namespace Common\Manager;

use Common\DAO\UserCodeDAO;
use Common\DAO\UserDAO;
use Common\Entity\Role;
use Common\Entity\User;
use Common\Entity\UserCode;
use Zend\Crypt\Password\Bcrypt;
use Zend\Math\Rand;

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

        return $code;
    }

    public function removeUser(User $user)
    {
        $user->setDeleted(User::DELETED_YES);
        $this->saveUser($user, false);
    }

    public function getTypesForSelect()
    {
        return [
            User::TYPE_COMPANY => $this->getTranslatorManager()->translate('Company'),
            User::TYPE_USER => $this->getTranslatorManager()->translate('User'),
        ];
    }

    public function getTypeNameByIdType($idType)
    {
        $selectData = $this->getTypesForSelect();

        if (isset($selectData[$idType])) {
            return $selectData[$idType];
        }
        return $idType;
    }

    public function getStatusForSelect()
    {
        return [
            User::STATUS_ACTIVE => $this->getTranslatorManager()->translate('Active'),
            User::STATUS_NO_ACTIVE => $this->getTranslatorManager()->translate('No active'),
            User::STATUS_BANNED => $this->getTranslatorManager()->translate('Banned'),
        ];
    }

    public function getStatusNameByIdStatus($idStatus)
    {
        $selectData = $this->getStatusForSelect();

        if (isset($selectData[$idStatus])) {
            return $selectData[$idStatus];
        }

        return $idStatus;
    }

    public function saveUser(User $user, $createCode = true)
    {
        if ($this->isNewEntity($user)) {

            $salt = Rand::getString(Bcrypt::MIN_SALT_SIZE);
            $secure = new Bcrypt(['salt' => $salt]);
            $password = $this->generatePassword();

            $user->setSalt($salt)
                ->setPassword($secure->create($password));
        }

        if ($createCode) {
            $user->setCode($this->createUserCode($user, UserCode::TYPE_REGISTRATION));
        }

        $this->getDAO()->save($user);

    }

    protected function generatePassword($length = 8) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $count = mb_strlen($chars);

        for ($i = 0, $result = ''; $i < $length; $i++) {
            $index = rand(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }

        return $result;
    }

    public function getListDataForTable($offset, $limit)
    {
        return [
            'count' => $this->getDAO()->countAll(),
            'data' => $this->getDAO()->findAllOffsetAndLimit($offset*$limit, $limit)
        ];
    }

    public function getDeletedNameForSelect()
    {
        return [
            User::DELETED_NO => $this->getTranslatorManager()->translate('No'),
            User::DELETED_YES => $this->getTranslatorManager()->translate('Yes'),
        ];
    }

    public function getDeletedNameByIdDeleted($idDeleted)
    {
        $selectData = $this->getDeletedNameForSelect();

        if (isset($selectData[$idDeleted])) {
            return $selectData[$idDeleted];
        }
        return $selectData[$idDeleted];
    }

    public static function getRolesForSelect()
    {
        return [
            1 => Role::ROLE_GUEST,
            2 => Role::ROLE_USER,
            3 => Role::ROLE_MANAGER,
            4 => Role::ROLE_ADMIN,
            5 => Role::ROLE_GOD_MODE
        ];
    }

}