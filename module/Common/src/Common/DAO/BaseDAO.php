<?php

namespace Common\DAO;


use Zend\ServiceManager\ServiceManager;
use \Doctrine\Orm\AbstractQuery;
use \Doctrine\ORM\EntityManager;

abstract class BaseDAO
{

    /**
     * @var string
     */
    protected $repositoryName = null;

    /**
     * @var EntityManager|null
     */
    protected $entityManager = null;
    /**
     * @var ServiceManager|null
     */
    protected $serviceManager = null;

    /**
     * @return string
     */
    abstract function getRepositoryName();

    /**
     * @param ServiceManager $sm
     */
    public function __construct(ServiceManager $sm)
    {
        $this->setServiceManager($sm);
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        if ($this->entityManager === null) {
            $this->entityManager = $this->serviceManager->get('doctrine.entitymanager.orm_default');
        }

        return $this->entityManager;
    }

    /**
     * @param null $entityManager
     * @return $this
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;

        return $this;
    }

    /**
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     * @return $this
     */
    public function setServiceManager($serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }

    /**
     * @return \Zend\ServiceManager\ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * @param $id
     * @param int $hydrationMode
     * @param bool $useCache
     * @return mixed
     */
    public function findById($id, $hydrationMode = AbstractQuery::HYDRATE_OBJECT, $useCache = true)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('em')
            ->from($this->getRepositoryName(), 'em')
            ->where($qb->expr()->eq('em.id', ':id'))
            ->setParameter('id', $id);

        return $qb->getQuery()->useResultCache($useCache, null)->getOneOrNullResult($hydrationMode);

    }

    /**
     * @param int $hydrationMode
     * @param bool $useCache
     * @return array
     */
    public function findAll($hydrationMode = AbstractQuery::HYDRATE_OBJECT, $useCache = true)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('em')
            ->from($this->getRepositoryName(), 'em');

        return $qb->getQuery()->useResultCache($useCache, null)->getResult($hydrationMode);
    }


    /**
     * @param $entity
     * @param bool $flush
     * @return $this
     */
    public function save($entity, $flush = true)
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }

        return $this;
    }

    /**
     * @param $entity
     * @param bool $flush
     * @return $this
     */
    public function remove($entity, $flush = true)
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function flush()
    {
        $this->getEntityManager()->flush();

        return $this;
    }



}