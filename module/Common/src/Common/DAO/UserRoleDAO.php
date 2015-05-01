<?php

namespace Common\DAO;

use Doctrine\ORM\AbstractQuery;

class UserRoleDAO extends BaseDAO
{

    public function getRepositoryName()
    {
        return 'Common\Entity\Role';
    }

    /**
     * @param $roleName
     * @param int $hydrationMode
     * @param bool $useCache
     * @return null|\Common\Entity\Role
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByRoleName($roleName, $hydrationMode = AbstractQuery::HYDRATE_OBJECT, $useCache = true)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('e')
            ->from($this->getRepositoryName(), 'e')
            ->andWhere($qb->expr()->eq('e.name', ':name'))
            ->setParameters(array(
                'name' => $roleName,
            ));

        return $qb->getQuery()->useResultCache($useCache)->getOneOrNullResult($hydrationMode);
    }

}