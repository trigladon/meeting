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
	 * @param int $hydrationMode
	 * @param bool $useCache
	 * @return array
	 */
	public function findAllJoinCode($hydrationMode = AbstractQuery::HYDRATE_OBJECT, $useCache = true)
	{
		$qb = $this->getEntityManager()->createQueryBuilder();
		$qb->select('em, uc')
			->from($this->getRepositoryName(), 'em')
			->leftJoin('em.code', 'uc')
		;

		return $qb->getQuery()->useResultCache($useCache, null)->getResult($hydrationMode);
	}

	/**
	 * @param      $offset
	 * @param      $limit
	 * @param int  $hydrationMode
	 * @param bool $useCache
	 *
	 * @return ArrayCollection
	 */
	public function findAllJoinOffsetAndLimit($offset, $limit, $hydrationMode = AbstractQuery::HYDRATE_OBJECT, $useCache = true)
	{
		$qb = $this->findAllQ();
		$qb->leftJoin('u.code', 'uc')->addSelect('uc')
			->setFirstResult($offset)
			->setMaxResults($limit);

		return $qb->getQuery()->useResultCache($useCache)->getResult($hydrationMode);
	}

}