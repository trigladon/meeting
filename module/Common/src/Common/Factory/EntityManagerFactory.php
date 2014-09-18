<?php

namespace Data\Factory;

use Doctrine\ORM\EntityManager;
use DoctrineModule\Service\AbstractFactory;
use Zend\ServiceManager\ServiceLocatorInterface;

class EntityManagerFactory extends AbstractFactory
{
    /**
     * {@inheritDoc}
     * @return EntityManager
     */
    public function createService(ServiceLocatorInterface $sl)
    {
        /* @var $options \DoctrineORMModule\Options\EntityManager */
        $options    = $this->getOptions($sl, 'entitymanager');
        $connection = $sl->get($options->getConnection());
        $config     = $sl->get($options->getConfiguration());
        $mainConfig = $sl->get('config');

        // initializing the resolver
        //       rely on its factory code
        $sl->get($options->getEntityResolver());
        $configTablePrefix = $mainConfig['doctrine']['connection']['orm_default']['params']['table_prefix'];

        // Table Prefix
        $tablePrefix = new \Common\DoctrineExtension\DoctrineTablePrefix($configTablePrefix);
        $evm = $connection->getEventManager();
        $evm->addEventListener(\Doctrine\ORM\Events::loadClassMetadata, $tablePrefix);

        return EntityManager::create($connection, $config, $evm);
    }

    /**
     * {@inheritDoc}
     */
    public function getOptionsClass()
    {
        return 'DoctrineORMModule\Options\EntityManager';
    }

}