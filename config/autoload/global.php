<?php
defined('DEFAULT_LANGUAGE') || define('DEFAULT_LANGUAGE', 'en');
return array(

    'projectData' => [
        'siteName' => 'Meeting',
        'defaultLanguage' => DEFAULT_LANGUAGE,
        'defaultLanguageId' => 2,
        'defaultLanguageLocale' => 'en_EN',

        'emailSend' => 'help@meeting.com',
        'emailSendName' => 'Meeting.com',

        'options' => [
            'dateTimeFormat' => 'Y-m-d h:i:s',
            'dateFormat' => 'Y-m-d',

            'dateTimeFormatJs' => 'yyyy-mm-dd hh:ii:ss',
            'dateFormatJs' => 'yyyy-mm-dd',
        ],

        'files' => [
            'size' => 2048000,
        ],

        'csrf' => [
            'timeout' => 1800,
        ],

    ],

    'cache' => [
        'cacheLifeTime' => 1800,
    ],

    'paths' => [
        'assetFolder' => '/backend',
    ],

    'email' =>array(
        'transport' => array(
            'name' => 'localhost',
            'host' => '127.0.0.1',
            'port' => 25,
            //            'name'              => 'localhost',
            //            'host'              => '127.0.0.1',
            //            'port'              => 25,//465,
            //            'connection_class'  => 'login',
            //            'connection_config' => array(
            //                'username'      => 'meeting',
            //                'password'      => '123123',
            //                'ssl'           => 'tls',
            //            ),
        ),
        'options' => array(
            'layout' => 'layout/email',
            'charset' => 'UTF-8',
        )
    ),

    'session' => array(
        'options' => [
            'remember_me_seconds' => 2419200,
            'use_cookies'       => true,
            'cookie_httponly'   => true,
            'cookie_lifetime'   => 2419200,
            'gc_maxlifetime'    => 2419200,
        ]
    ),
);
