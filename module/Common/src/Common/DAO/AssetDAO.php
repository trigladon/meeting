<?php

namespace Common\DAO;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\AbstractQuery;

class AssetDAO extends BaseDAO
{

    public function getRepositoryName()
    {
        return 'Common\Entity\Asset';
    }

    /**
     * @param      $offset
     * @param      $limit
     * @param int  $hydrationMode
     * @param bool $useCache
     *
     * @return ArrayCollection
     */
    public function findAllOffsetAndLimit($offset, $limit, $hydrationMode = AbstractQuery::HYDRATE_OBJECT, $useCache = true)
    {
        $qb = $this->findAllQ();
        $qb->leftJoin('u.user', 'uu')->addSelect('uu')
            ->leftJoin('uu.code', 'uuc')->addSelect('uuc')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        return $qb->getQuery()->useResultCache($useCache)->getResult($hydrationMode);
    }

}