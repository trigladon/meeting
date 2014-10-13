<?php

namespace Common\DAO;

use Common\Entity\User;
use Common\Entity\UserCode;
use Doctrine\ORM\AbstractQuery;

class UserCodeDAO extends BaseDAO
{
    public function getRepositoryName()
    {
        return 'Common\Entity\UserCode';
    }

    public function findByUserAndType(User $user, $type, $hydrationMode = AbstractQuery::HYDRATE_OBJECT, $useCache = true)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('c')
            ->from($this->getRepositoryName(), 'c')
            ->andWhere($qb->expr()->eq('c.user',':user'))
            ->andWhere($qb->expr()->eq('c.type', ':type'))
            ->setParameters(array(
                'user' => $user,
                'type' => $type,
            ));

        return $qb->getQuery()->useResultCache($useCache)->getOneOrNullResult($hydrationMode);
    }

}