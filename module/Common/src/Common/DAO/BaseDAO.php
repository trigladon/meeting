<?php

namespace Common\DAO;


use Doctrine\Common\Collections\ArrayCollection;
use Zend\ServiceManager\ServiceLocatorInterface;
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
     * @var ServiceLocatorInterface|null
     */
    protected $serviceManager = null;

    /**
     * @var Array
     */
    protected $cacheOption = null;

    /**
     * @return string
     */
    abstract function getRepositoryName();

    /**
     * @param ServiceLocatorInterface $sm
     */
    public function __construct(ServiceLocatorInterface $sm)
    {
        $this->setServiceManager($sm);
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        if ($this->entityManager === null) {
            $this->entityManager = $this->serviceManager->get('EntityManagerDoctrine');
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
     * Cache options
     *
     * @return array
     */
    protected function getCacheOptions()
    {
        if ($this->cacheOption === null) {
            $this->cacheOption = $this->getServiceManager()->get('config')['cache'];
        }
        return $this->cacheOption;
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

//        var_dump($qb->getQuery()); die();
        return $qb->getQuery()->useResultCache($useCache, $this->getCacheOptions()['cacheLifeTime'], md5($this->getRepositoryName().$id))->getOneOrNullResult($hydrationMode);

    }

    public function findByLocale($locale, $hydrationMode = AbstractQuery::HYDRATE_OBJECT, $useCache = true)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('em')
            ->from($this->getRepositoryName(), 'em')
            ->where($qb->expr()->eq('em.locale', ':locale'))
            ->setParameter('locale', $locale);

        return $qb->getQuery()->useResultCache($useCache, $this->getCacheOptions()['cacheLifeTime'], md5($this->getRepositoryName().$locale))->getOneOrNullResult($hydrationMode);
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

        return $qb->getQuery()->useResultCache($useCache, $this->getCacheOptions()['cacheLifeTime'], md5($this->getRepositoryName()))->getResult($hydrationMode);
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

        return $qb->getQuery()->useResultCache($useCache, $this->getCacheOptions()['cacheLifeTime'], md5($this->getRepositoryName().$offset.$limit))->getResult($hydrationMode);
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
        return $qb->getQuery()->useResultCache($useCache, $this->getCacheOptions()['cacheLifeTime'], md5($this->getRepositoryName().'countAll'))->getSingleScalarResult();
    }



}