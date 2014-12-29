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

    /**
     * @param BaseEntity $entity
     * @return string
     * @throws \Exception
     */
    protected function getEntityPublishedName(BaseEntity $entity)
    {
        return $this->getEnumName($entity);
    }

    protected function getEnumName(BaseEntity $entity, $property = 'published')
    {
        $method = 'get'.ucfirst($property);
        if (!is_callable(array($entity, $method))) {
            throw new \Exception('Method "'.$method.'()" not found '.get_class($entity));
        }

        return $entity->{$method}() === '0' ? 'no' : 'yes';
    }

}