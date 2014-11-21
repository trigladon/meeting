<?php

namespace Common\DAO;

use Common\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\AbstractQuery;

class UserDAO extends BaseDAO
{

    public function getRepositoryName()
    {
        return 'Common\Entity\User';
    }

    /**
     * @param      $email
     * @param int  $hydrationMode
     * @param bool $useCache
     *
     * @return User|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
	public function findByEmail($email, $hydrationMode = AbstractQuery::HYDRATE_OBJECT, $useCache = true)
	{
		$qb = $this->getEntityManager()->createQueryBuilder();
		$qb->select('u')
			->from($this->getRepositoryName(), 'u')
			->where($qb->expr()->eq('u.email', ':email'))
			->setParameters(['email' => $email]);

		return $qb->getQuery()->useResultCache($useCache)->getOneOrNullResult($hydrationMode);
	}

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findAllQ()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        return$qb->select('u')->from($this->getRepositoryName(), 'u');
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
            ->setMaxResults($limit);

        return $qb->getQuery()->useResultCache($useCache)->getResult($hydrationMode);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function countQ()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        return $qb->select('COUNT(u.id)')->from($this->getRepositoryName(), 'u');
    }

    /**
     * @param bool $useCache
     *
     * @return null|mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countAll($useCache = true)
    {
        $qb = $this->countQ();
        //var_dump($qb->getQuery()->getSQL()); die();
        return $qb->getQuery()->useResultCache($useCache)->getSingleScalarResult();
    }

}