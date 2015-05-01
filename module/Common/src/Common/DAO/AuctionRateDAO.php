<?php

namespace Common\DAO;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\AbstractQuery;

class AuctionRateDAO extends BaseDAO
{

    public function getRepositoryName()
    {
        return 'Common\Entity\AuctionRate';
    }

    /**
     * @param int $id
     * @param bool $useCache
     *
     * @return null|mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countAllNew($id, $useCache = true)
    {
        $qb = $this->countQ();
        $qb->innerJoin('u.auction', 'ua')
            ->andWhere($qb->expr()->eq('ua.id' , ':id'))
            ->setParameter('id', $id);

        return $qb->getQuery()->useResultCache($useCache)->getSingleScalarResult();
    }

    /**
     * @param      $id
     * @param      $offset
     * @param      $limit
     * @param int  $hydrationMode
     * @param bool $useCache
     *
     * @return ArrayCollection
     */
    public function findAllOffsetAndLimitNew($id, $offset, $limit, $hydrationMode = AbstractQuery::HYDRATE_OBJECT, $useCache = true)
    {
        $qb = $this->findAllQ();
        $qb->innerJoin('u.auction', 'ua')->addSelect('ua')
            ->andWhere($qb->expr()->eq('ua.id' , ':id'))
            ->setParameter('id', $id)
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        return $qb->getQuery()->useResultCache($useCache)->getResult($hydrationMode);
    }

}