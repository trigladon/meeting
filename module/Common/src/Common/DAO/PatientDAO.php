<?php

namespace Common\DAO;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\AbstractQuery;

class PatientDAO extends BaseDAO
{

    public function getRepositoryName()
    {
        return 'Common\Entity\Patient';
    }


    public function findByIdJoinAsset($id, $hydrationMode = AbstractQuery::HYDRATE_OBJECT, $useCache = true)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('em, pa, da')
            ->from($this->getRepositoryName(), 'em')
            ->leftJoin('em.image', 'pa')
            ->leftJoin('em.assets', 'da')
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
        $qb->leftJoin('u.user', 'uu')->addSelect('uu')
            ->leftJoin('uu.code', 'uuc')->addSelect('uuc')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        return $qb->getQuery()->useResultCache($useCache)->getResult($hydrationMode);
    }

}