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
foreach(Company::model()->findAll(array(
    'condition' => 'type = :type',
    'params' => array(':type' => Company::COMPANY_TYPE),
    'order' => 'type DESC'
)) as $company) {
    if($model->companyType == Company::SERVICE_TYPE) {
        $model->partner_id = $company->id;
        foreach ($model->search()->getData() as $data) {
            $filename = "Акт $company->name от " . date('d-m-Y', strtotime($data->service_date)) . ".xls";
            $fullFilename = str_replace(' ', '_', str_replace('"', '', "$path/$filename"));
            if(file_exists($fullFilename)) {
                echo CHtml::link($filename, '/' . $fullFilename) . '<br /><br />';
            } else {
                //echo "<span class='error'>$filename</span><br /><br />";
            }
            $filename = "Счет $company->name от " . date('d-m-Y', strtotime($data->service_date)) . ".xls";
            $fullFilename = str_replace(' ', '_', str_replace('"', '', "$path/$filename"));
            if(file_exists($fullFilename)) {
                echo CHtml::link($filename, '/' . $fullFilename) . '<br /><br />';
            } else {
                //echo "<span class='error'>$filename</span><br /><br />";
            }
        }
    } else {
        $model->partner_id = $company->id;
        if (!$model->search()->getData()) {
            continue;
        }
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
}