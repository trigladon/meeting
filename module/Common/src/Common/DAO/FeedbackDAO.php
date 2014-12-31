<?php

namespace Common\DAO;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\AbstractQuery;

class FeedbackDAO extends BaseDAO
{

    public function getRepositoryName()
    {
        return 'Common\Entity\Feedback';
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
        $qb->setFirstResult($offset)
            ->setMaxResults($limit)
            ->groupBy('u.status')
            ->addGroupBy('u.created')
        ;

        return $qb->getQuery()->useResultCache($useCache)->getResult($hydrationMode);
    }


}