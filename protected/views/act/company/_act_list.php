<?php
/**
 * @var $this ActController
 * @var $model Act
 */

$time = strtotime($model->month . '-01 00:00:00');
$path = "acts/" . date('m-Y', $time);

if(file_exists("$path/" . Company::CARWASH_TYPE . ".zip")) {
    echo '<strong>' . CHtml::link('Скачать одним файлом', "/$path/" . Company::COMPANY_TYPE . ".zip") . '</strong><br /><br />';
}
foreach(Company::model()->findAll(array(
    'condition' => 'type = :type',
    'params' => array(':type' => Company::COMPANY_TYPE),
    'order' => 'type DESC'
)) as $company) {
    $filename = "Акт $company->name от " . date('m-Y', $time) . ".xls";
    $fullFilename = str_replace(' ', '_', str_replace('"', '', "$path/$filename"));
    if(file_exists($fullFilename)) {
        echo CHtml::link($filename, '/' . $fullFilename) . '<br /><br />';
    } else {
        //echo "<span class='error'>$filename</span><br /><br />";
    }
    $filename = "Счет $company->name от " . date('m-Y', $time) . ".xls";
    $fullFilename = str_replace(' ', '_', str_replace('"', '', "$path/$filename"));
    if(file_exists($fullFilename)) {
        echo CHtml::link($filename, '/' . $fullFilename) . '<br /><br />';
    } else {
        //echo "<span class='error'>$filename</span><br /><br />";
    }
}