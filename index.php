<?php
error_reporting(E_ALL);
if(!function_exists('mb_ucfirst')) {
    function mb_ucfirst($str, $enc = 'utf-8') {
        return mb_strtoupper(mb_substr($str, 0, 1, $enc), $enc).mb_substr($str, 1, mb_strlen($str, $enc), $enc);
    }
}
$config = dirname(__FILE__) . '/protected/config/main.php';
$yii = '../../framework/yii.php';

if ($_SERVER['HTTP_HOST'] == 'docs.mts') {
    $config = dirname(__FILE__) . '/protected/config/test.php';
}

if (strpos($_SERVER['HTTP_HOST'], 'demo') !== false) {
    $config = dirname(__FILE__) . '/protected/config/demo.php';
}

defined('YII_DEBUG') or define('YII_DEBUG', 0);

require_once($yii);
Yii::createWebApplication($config)->run();
