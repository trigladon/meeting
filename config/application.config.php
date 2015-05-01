<?php


return array(

    'modules' => array(
        'Common',
        'ZendDeveloperTools',
        'ZfcRbac',
        'DoctrineModule',
        'DoctrineORMModule',
        'Application',
        'Admin',
    ),

    'module_listener_options' => array(

        'module_paths' => array(
            './module',
            './vendor',
        ),

        'config_glob_paths' => array(
            'config/autoload/{,*.}{global,local}.php',
        ),

//        'config_cache_enabled' => true,
//        'config_cache_key' => md5('config'),
//        'module_map_cache_enabled' => true,
//        'module_map_cache_key' => md5('module_map'),
//        'cache_dir' => "./data/cache",

//        'check_dependencies' => true,
    ),

    // Used to create an own service manager. May contain one or more child arrays.
    //'service_listener_options' => array(
    //     array(
    //         'service_manager' => $stringServiceManagerName,
    //         'config_key'      => $stringConfigKey,
    //         'interface'       => $stringOptionalInterface,
    //         'method'          => $stringRequiredMethodName,
    //     ),
    // )

   // Initial configuration with which to seed the ServiceManager.
   // Should be compatible with Zend\ServiceManager\Config.
//   'service_manager' => array(
//       'factories' => array(
//           'auth.service' => 'Common\Factory\Auth\AuthFactory',
//       )
//   ),
);
