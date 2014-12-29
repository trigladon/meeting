<?php

namespace Common\DAO;

use Doctrine\ORM\AbstractQuery;

class CityDAO extends BaseDAO
{

    public function getRepositoryName()
    {
        return 'Common\Entity\City';
    }

    public function findByIdJoin($id, $hydrationMode = AbstractQuery::HYDRATE_OBJECT, $useCache = true)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('em, c')
            ->from($this->getRepositoryName(), 'em')
            ->innerJoin('em.country', 'c')
            ->where($qb->expr()->eq('em.id', ':id'))
            ->setParameter('id', $id);

        return $qb->getQuery()->useResultCache($useCache, null)->getOneOrNullResult($hydrationMode);

    }

    /**
     * @param      $offset
     * @param      $limit
     * @param int  $hydrationMode
     * @param bool $useCache
     *
     * @return ArrayCollection
     */
    public function findAllJoinOffsetAndLimit($offset, $limit, $hydrationMode = AbstractQuery::HYDRATE_OBJECT, $useCache = true)
    {
        $qb = $this->findAllQ();
        $qb->innerJoin('u.country', 'uc')->addSelect('uc')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        return $qb->getQuery()->useResultCache($useCache)->getResult($hydrationMode);
    }

}