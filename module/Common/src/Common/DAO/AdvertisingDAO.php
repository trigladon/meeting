<?php

namespace Common\DAO;


use Doctrine\ORM\AbstractQuery;

class AdvertisingDAO extends BaseDAO
{

    public function getRepositoryName()
    {
        return 'Common\Entity\Advertising';
    }

    public function findAllAdvertisingOffsetAndLimit($offset, $limit, $languagePrefix, $hydrationMode = AbstractQuery::HYDRATE_OBJECT, $useCache = true)
    {
        $qb = $this->findAllQ();
        $qb->leftJoin('u.translations', 'ut')->addSelect('ut')
            ->innerJoin('ut.language', 'utl')
            ->innerJoin('u.place', 'up')->addSelect('up')
            ->where($qb->expr()->eq('utl.prefix', ":prefix"))
            ->setParameter('prefix', $languagePrefix)
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        return $qb->getQuery()->useResultCache($useCache)->getResult($hydrationMode);

    }

    public function findByIdJoin($id, $hydrationMode = AbstractQuery::HYDRATE_OBJECT, $useCache = true)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('em')
            ->from($this->getRepositoryName(), 'em')
            ->innerJoin('em.translations', 'emt')->addSelect('emt')
            ->innerJoin('emt.language', 'emtl')->addSelect('emtl')
            ->leftJoin('em.image', 'emi')->addSelect('emi')
            ->where($qb->expr()->eq('em.id', ':id'))
            ->setParameter('id', $id);

        return $qb->getQuery()->useResultCache($useCache, null)->getOneOrNullResult($hydrationMode);

    }

}