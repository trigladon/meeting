<?php

namespace Common\DAO;

use Common\Entity\Language;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\AbstractQuery;

class PageDAO extends BaseDAO
{

    public function getRepositoryName()
    {
        return 'Common\Entity\Page';
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
    public function findPagesOffsetAndLimit($offset, $limit, $languagePrefix, $hydrationMode = AbstractQuery::HYDRATE_OBJECT, $useCache = true)
    {
        $qb = $this->findAllQ();
        $qb->innerJoin('u.translations', 'ut')->addSelect('ut')
            ->innerJoin('ut.language', 'utl')->addSelect('utl')
            ->where($qb->expr()->eq('utl.prefix', ':prefix'))
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
            ->leftJoin('em.translations', 'ut')->addSelect('ut')
            ->leftJoin('ut.language', 'la')->addSelect('la')
            ->where($qb->expr()->eq('em.id', ':id'))
            ->setParameter('id', $id);

        return $qb->getQuery()->useResultCache($useCache, null)->getOneOrNullResult($hydrationMode);

    }

}