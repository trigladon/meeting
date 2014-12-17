<?php

namespace Common\Manager;

use Common\DAO\LanguageDAO;

class LanguageManager extends BaseEntityManager
{
    protected $dao = null;

    public function getDAO()
    {
        if ($this->dao === null) {
            $this->dao = new LanguageDAO($this->getServiceLocator());
        }
        return $this->dao;
    }

}