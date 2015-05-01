<?php

namespace Common\DAO;

use Doctrine\ORM\AbstractQuery;

class AuctionDAO extends BaseDAO
{

    public function getRepositoryName()
    {
        return 'Common\Entity\Auction';
    }

    public function findByIdJoin($id, $hydrationMode = AbstractQuery::HYDRATE_OBJECT, $useCache = true)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('em')
            ->from($this->getRepositoryName(), 'em')
            ->leftJoin('em.image', 'emi')->addSelect('emi')
            ->leftJoin('em.video', 'emv')->addSelect('emv')
            ->leftJoin('em.user', 'emu')->addSelect('emu')
            ->leftJoin('em.patient', 'emp')->addSelect('emp')
            ->where($qb->expr()->eq('em.id', ':id'))
            ->setParameter('id', $id);

        return $qb->getQuery()->useResultCache($useCache, null)->getOneOrNullResult($hydrationMode);
    }

}