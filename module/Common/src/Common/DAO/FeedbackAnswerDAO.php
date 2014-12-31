<?php

namespace Common\DAO;

use Doctrine\ORM\AbstractQuery;

class FeedbackAnswerDAO extends BaseDAO
{

    public function getRepositoryName()
    {
        return 'Common\Entity\FeedbackAnswer';
    }

    /**
     * @param $id
     * @param int $hydrationMode
     * @param bool $useCache
     * @return mixed
     */
    public function findByIdJoin($id, $hydrationMode = AbstractQuery::HYDRATE_OBJECT, $useCache = true)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('em')
            ->from($this->getRepositoryName(), 'em')
            ->innerJoin('em.feedback', 'emf')->addSelect('emf')
            ->where($qb->expr()->eq('em.id', ':id'))
            ->setParameter('id', $id);

        return $qb->getQuery()->useResultCache($useCache, null)->getOneOrNullResult($hydrationMode);

    }

}