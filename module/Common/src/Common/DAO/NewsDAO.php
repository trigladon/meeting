<?php

namespace Common\DAO;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\AbstractQuery;

class NewsDAO extends BaseDAO
{

    public function getRepositoryName()
    {
        return 'Common\Entity\News';
    }

    /**
     * @param      $offset
     * @param      $limit
     * @param string $languagePrefix
     * @param int  $hydrationMode
     * @param bool $useCache
     *
     * @return ArrayCollection
     */
    public function findNewsOffsetAndLimit($offset, $limit, $languagePrefix, $hydrationMode = AbstractQuery::HYDRATE_OBJECT, $useCache = true)
    {
        $qb = $this->findAllQ();
        $qb->innerJoin('u.translations', 'ut')->addSelect('ut')
            ->innerJoin('ut.language', 'utl')->addSelect('utl')
            ->innerJoin('u.user', 'uu')->addSelect('uu')
            ->leftJoin('uu.code', 'uuc')->addSelect('uuc')
            ->leftJoin('u.category', 'uc')->addSelect('uc')
            ->innerJoin('uc.translations', 'uct')->addSelect('uct')
            ->innerJoin('uct.language', 'ucl')->addSelect('ucl')
            ->where($qb->expr()->eq('ucl.prefix', ':prefix'))
            ->andWhere($qb->expr()->eq('utl.prefix', ':prefix'))
            ->setParameters(['prefix' => $languagePrefix])
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        return $qb->getQuery()->useResultCache($useCache)->getResult($hydrationMode);
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
            ->leftJoin('em.category', 'uc')->addSelect('uc')
            ->leftJoin('em.translations', 'ut')->addSelect('ut')
            ->leftJoin('ut.language', 'la')->addSelect('la')
            ->where($qb->expr()->eq('em.id', ':id'))
            ->setParameter('id', $id);

        return $qb->getQuery()->useResultCache($useCache, null)->getOneOrNullResult($hydrationMode);

    }

}