<?php

namespace Common\Manager;

use Common\DAO\CommentDAO;

class CommentManager extends BaseEntityManager
{

    protected $dao = null;

    /**
     * @return CommentDAO
     */
    public function getDAO()
    {
        if ($this->dao === null) {
            $this->dao = new CommentDAO($this->getServiceLocator());
        }
        return $this->dao;
    }



}