<?php

namespace Common\DAO;

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

}