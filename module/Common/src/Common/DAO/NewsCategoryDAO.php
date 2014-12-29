<?php

namespace Common\DAO;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\AbstractQuery;

class NewsCategoryDAO extends BaseDAO
{

    public function getRepositoryName()
    {
        return 'Common\Entity\NewsCategory';
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

    /**
     * @param $languagePrefix
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findAllCategoriesQ($languagePrefix)
    {
        $qb = $this->findAllQ();
        return $qb->innerJoin('u.translations', 'ut')->addSelect('ut')
            ->innerJoin('u.user', 'uu')->addSelect('uu')
            ->leftJoin('uu.code', 'uuc')->addSelect('uuc')
            ->innerJoin('ut.language', 'utl')->addSelect('utl')
            ->where($qb->expr()->eq('utl.prefix', ':prefix'))
            ->setParameters(['prefix' => $languagePrefix]);
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
    public function findCategoriesOffsetAndLimit($offset, $limit, $languagePrefix, $hydrationMode = AbstractQuery::HYDRATE_OBJECT, $useCache = true)
    {
        $qb = $this->findAllCategoriesQ($languagePrefix);
        $qb->setFirstResult($offset)
            ->setMaxResults($limit);

        return $qb->getQuery()->useResultCache($useCache)->getResult($hydrationMode);
    }

    /**
     * @param      $languagePrefix
     * @param int  $hydrationMode
     * @param bool $useCache
     *
     * @return ArrayCollection
     */
    public function findAllCategories($languagePrefix,  $hydrationMode = AbstractQuery::HYDRATE_OBJECT, $useCache = true)
    {
        $qb = $this->findAllCategoriesQ($languagePrefix);
        return $qb->getQuery()->useResultCache($useCache)->getResult($hydrationMode);
    }


}