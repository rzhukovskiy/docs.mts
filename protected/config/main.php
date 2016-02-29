<?php

return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Docs',
    'language' => 'ru',
    'sourceLanguage' => 'en',
    'preload' => array('log'),
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.wrappers.*',
        'application.extensions.*',
        'application.controllers.*',
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
        'db' => require(dirname(__FILE__) . '/db.php'),
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
                    'class' => 'CProfileLogRoute',
                    'levels' => 'profile',
                    'enabled' => 0,
                ),
                array(
                    'logFile'=>'notify.log',
                    'class'=>'CFileLogRoute',
                    'levels'=>'info,error,warning',
                    'categories'=>'notificator.*',
                ),
                array(
                    'logFile'=>'nms.log',
                    'class'=>'CFileLogRoute',
                    'levels'=>'info,error,warning',
                    'categories'=>'nms.*',
                ),
                array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'', //trace,
                    'categories'=>'system.*',
                    'filter'=>'CLogFilter',
                ),
            ),
        ),
        'authManager'=>array(
            'class'=>'PhpAuthManager',
            'defaultRoles' => array('guest'),
        ),
    ),
    'params' => require(dirname(__FILE__) . '/params.php'),
);