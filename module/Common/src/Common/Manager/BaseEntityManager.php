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

    /**
     * @param $offset
     * @param $limit
     *
     * @return array
     */
    public function getListDataForTable($offset, $limit)
    {
        return [
            'count' => $this->getDAO()->countAll(),
            'data' => $this->getDAO()->findAllOffsetAndLimit($offset, $limit)
        ];
    }

    protected function createHash($string)
    {
        return md5($string);
    }


}