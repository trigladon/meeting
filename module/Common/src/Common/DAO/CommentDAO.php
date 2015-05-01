<?php

namespace Common\DAO;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\AbstractQuery;

class CommentDAO extends BaseDAO
{

    public function getRepositoryName()
    {
        return 'Common\Entity\Comment';
    }

    /**
     * @param      $type
     * @param      $idData
     * @param int  $offset
     * @param bool $limit
     * @param int  $hydrationMode
     * @param bool $useCache
     *
     * @return ArrayCollection
     */
    public function findAllOffsetAndLimitNew($type, $idData, $offset, $limit, $hydrationMode = AbstractQuery::HYDRATE_OBJECT, $useCache = true)
    {
        $qb = $this->findAllQ();
        $qb->where($qb->expr()->eq('u.type', ':type'))
            ->andWhere($qb->expr()->eq('u.data', ':data'))
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->setParameters([
                'type' => $type,
                'data' => $idData,
            ]);

        return $qb->getQuery()->useResultCache($useCache)->getResult($hydrationMode);
    }

    /**
     * @param      $type
     * @param      $idData
     * @param bool $useCache
     *
     * @return null|mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countAllNew($type, $idData, $useCache = true)
    {
        $qb = $this->countQ();
        $qb = $qb->where($qb->expr()->eq('u.type', ':type'))
            ->andWhere($qb->expr()->eq('u.data', ':data'))
            ->setParameters([
                'type' => $type,
                'data' => $idData,
            ])
        ;
        return $qb->getQuery()->useResultCache($useCache)->getSingleScalarResult();
    }

}