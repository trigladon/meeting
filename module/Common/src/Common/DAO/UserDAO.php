<?php

namespace Common\DAO;

use Common\Entity\User;
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

}