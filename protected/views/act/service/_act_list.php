<?php
/**
 * @var $this ActController
 * @var $model Act
 */

$time = strtotime($model->service_date);
$path = "acts/" . date('m-Y', $time);

if(file_exists("$path/" . Company::CARWASH_TYPE . ".zip")) {
    echo '<strong>' . CHtml::link('Скачать одним файлом', "/$path/" . Company::CARWASH_TYPE . ".zip") . '</strong><br /><br />';
}
foreach(Company::model()->findAll(array(
    'condition' => 'type = :type',
    'params' => array(':type' => Company::CARWASH_TYPE),
    'order' => 'type DESC'
)) as $company) {
    $filename = "Акт $company->name от " . date('m-Y', $time) . ".xls";
    $fullFilename = str_replace(' ', '_', str_replace('"', '', "$path/$filename"));
    if(file_exists($fullFilename)) {
        echo CHtml::link($filename, '/' . $fullFilename) . '<br /><br />';
    } else {
        //echo "<span class='error'>$filename</span><br /><br />";
    }
}