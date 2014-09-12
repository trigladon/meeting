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
        )
    ),

];