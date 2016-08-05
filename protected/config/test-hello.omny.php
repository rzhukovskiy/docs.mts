<?php

    return array(
        'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
        'name' => 'Docs',
        'language' => 'ru',
        'sourceLanguage' => 'en',
        'preload' => array(
            'log',
            'debug'
        ),
        'import' => array(
            'application.models.*',
            'application.components.*',
            'application.wrappers.*',
        ),
        'defaultController' => 'home',
        'components' => array(
            'ih'=>array(
                'class'=>'CImageHandler',
            ),
            'user' => array(
                'class' => 'WebUser',
                'allowAutoLogin' => true,
                'loginUrl' => array('home/login'),
            ),
            'db' => require(dirname(__FILE__) . '/test-db-hello.omny.php'),
            'cache' => array(
                'class' => 'system.caching.CFileCache',
            ),
            'urlManager' => array(
                'urlFormat' => 'path',
                'showScriptName' => false,
                'rules' => require(dirname(__FILE__) . '/urlrules.php')
            ),
            'errorHandler' => array(
                'class' => 'CErrorHandler',
                'errorAction' => 'home/error',
            ),
            'log' => array(
                'class' => 'CLogRouter',
                'routes' => array(
                    array(
                        'class' => 'CFileLogRoute',
                        'levels' => 'error, warning',
                    ),
//                    array(
//                        'class'=>'CWebLogRoute',
//                    ),
                ),
            ),

            'debug' => array(
                'class' => 'ext.yii2-debug.Yii2Debug', // manual installation
            ),
            'authManager'=>array(
                'class'=>'PhpAuthManager',
                'defaultRoles' => array('guest'),
            ),
        ),
        'params' => require(dirname(__FILE__) . '/params.php'),
    );