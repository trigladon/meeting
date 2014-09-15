<?php

return [
    'doctrine' => array(
        'driver' => array(
            'data_entities' => array(
                'class' =>'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Data/Entity')
            ),

            'orm_default' => array(
                'drivers' => array(
                    'Data\Entity' => 'data_entities'
                )
            )
        ),
        'authentication' => array(
            'orm_default' => array(
                'object_manager' => 'Doctrine\ORM\EntityManager',
                'identity_class' => 'Data\Entity\User',
                'identity_property' => 'email',
                'credential_property' => 'password',
                'credential_callable' => array('Data\Manager\AuthManager', 'credentialCallable')
            ),
        ),
    ),

];