<?php
/**
 * @var $this ActController
 * @var $model Act
 */

$time = strtotime($model->month . '-01 00:00:00');
$path = "acts/" . date('m-Y', $time) . "/$model->companyType";

if(file_exists("$path/$model->companyType.zip")) {
    echo '<strong>' . CHtml::link('Скачать одним файлом', "/$path/$model->companyType.zip") . '</strong><br /><br />';
}
foreach($model->getClientsByType($model->companyType) as $actClient) {
    if($model->companyType == Company::SERVICE_TYPE) {
        $model->client_id = $actClient->client_id;
        foreach ($model->search()->getData() as $data) {
            $filename = "Акт {$actClient->client->name} - {$data->number} - {$data->id} от " . date('d-m-Y', strtotime($data->service_date)) . ".xls";
            $fullFilename = str_replace(' ', '_', str_replace('"', '', "$path/$filename"));
            if(file_exists($fullFilename)) {
                echo CHtml::link($filename, '/' . $fullFilename) . '<br /><br />';
            } else {
                //echo "<span class='error'>$filename</span><br /><br />";
            }
            $filename = "Счет {$actClient->client->name} - {$data->number} - {$data->id} от " . date('d-m-Y', strtotime($data->service_date)) . ".xls";
            $fullFilename = str_replace(' ', '_', str_replace('"', '', "$path/$filename"));
            if(file_exists($fullFilename)) {
                echo CHtml::link($filename, '/' . $fullFilename) . '<br /><br />';
            } else {
                //echo "<span class='error'>$filename</span><br /><br />";
            }
        }
    } else {
        $model->client_id = $actClient->client_id;
        if (!$model->search()->getData()) {
            continue;
        }
        if($model->companyType == Company::DISINFECTION_TYPE) {
            $files = 0;
            while (true) {
                $files++;
                $filename = "Справка {$actClient->client->name} от " . date('m-Y', $time) . "-$files.xlsx";
                $fullFilename = str_replace(' ', '_', str_replace('"', '', "$path/$filename"));
                if(file_exists($fullFilename)) {
                    echo CHtml::link($filename, '/' . $fullFilename) . '<br /><br />';
                } else {
                    break;
                }
            }

            $files = 0;
            while (true) {
                $files++;
                $filename = "Доп. справка {$actClient->client->name} от " . date('m-Y', $time) . "-$files.xlsx";
                $fullFilename = str_replace(' ', '_', str_replace('"', '', "$path/$filename"));
                if(file_exists($fullFilename)) {
                    echo CHtml::link($filename, '/' . $fullFilename) . '<br /><br />';
                } else {
                    break;
                }
            }
        }
        $filename = "Акт {$actClient->client->name} от " . date('m-Y', $time) . ".xls";
        $fullFilename = str_replace(' ', '_', str_replace('"', '', "$path/$filename"));
        if(file_exists($fullFilename)) {
            echo CHtml::link($filename, '/' . $fullFilename) . '<br /><br />';
        } else {
            //echo "<span class='error'>$filename</span><br /><br />";
        }
        if($model->companyType == Company::DISINFECTION_TYPE) {
            $filename = "Доп. акт {$actClient->client->name} от " . date('m-Y', $time) . ".xls";
            $fullFilename = str_replace(' ', '_', str_replace('"', '', "$path/$filename"));
            if (file_exists($fullFilename)) {
                echo CHtml::link($filename, '/' . $fullFilename) . '<br /><br />';
            } else {
                //echo "<span class='error'>$filename</span><br /><br />";
            }
        }

        $filename = "Счет {$actClient->client->name} от " . date('m-Y', $time) . ".xls";
        $fullFilename = str_replace(' ', '_', str_replace('"', '', "$path/$filename"));
        if(file_exists($fullFilename)) {
            echo CHtml::link($filename, '/' . $fullFilename) . '<br /><br />';
        } else {
            //echo "<span class='error'>$filename</span><br /><br />";
        }
        if($model->companyType == Company::DISINFECTION_TYPE) {
            $filename = "Доп. счет {$actClient->client->name} от " . date('m-Y', $time) . ".xls";
            $fullFilename = str_replace(' ', '_', str_replace('"', '', "$path/$filename"));
            if (file_exists($fullFilename)) {
                echo CHtml::link($filename, '/' . $fullFilename) . '<br /><br />';
            } else {
                //echo "<span class='error'>$filename</span><br /><br />";
            }
        }
    }
}