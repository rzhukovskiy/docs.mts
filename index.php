<?php
error_reporting(E_ALL);
$config = dirname(__FILE__) . '/protected/config/main.php';
$yii = '../../framework/yii.php';

if ($_SERVER['HTTP_HOST'] == 'docs.mts') {
    $config = dirname(__FILE__) . '/protected/config/test.php';
}

defined('YII_DEBUG') or define('YII_DEBUG', 0);

require_once($yii);
Yii::createWebApplication($config)->run();
