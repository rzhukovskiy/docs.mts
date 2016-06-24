<?php
/**
 * @var $this ActController
 * @var $model Act
 */

$time = strtotime($model->month . '-01 00:00:00');
$path = "acts/" . date('m-Y', $time);

if(file_exists("$path/$model->companyType.zip")) {
    echo '<strong>' . CHtml::link('Скачать одним файлом', "/$path/$model->companyType.zip") . '</strong><br /><br />';
}
foreach($model->getPartnersByType($model->companyType) as $actPartner) {
    $filename = "Акт {$actPartner->partner->name} от " . date('m-Y', $time) . ".xls";
    $fullFilename = str_replace(' ', '_', str_replace('"', '', "$path/$filename"));
    if(file_exists($fullFilename)) {
        echo CHtml::link($filename, '/' . $fullFilename) . '<br /><br />';
    } else {
        echo "<span class='error'>$filename</span><br /><br />";
    }
}