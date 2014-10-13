<?php

return array(
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'doctrine_type_mappings' => array(
                    'enum' => 'string'
                ),
                'params' => array(
                    'host' => 'localhost',
                    'port' => 3306,
                    'user' => 'root',
                    'password' => 'root',
                    'dbname' => 'meet',
                    'table_prefix' => 'o_o_',
                    'driverOptions' => array(
                        \PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
                        \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
                    ),
                )
            )
        )
    )
);
