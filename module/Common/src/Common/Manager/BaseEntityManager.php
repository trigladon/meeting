<?php

namespace Common\Manager;

use Common\DAO\BaseDAO;
use Common\Entity\BaseEntity;

abstract class BaseEntityManager extends BaseManager
{

    /**
     * @return BaseDAO
     */
	abstract function getDAO();

    protected function isNewEntity(BaseEntity $entity)
    {
        return $this->getDAO()->getEntityManager()->getUnitOfWork()->getEntityState($entity) === \Doctrine\ORM\UnitOfWork::STATE_NEW;
    }


}