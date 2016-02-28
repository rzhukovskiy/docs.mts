<?php
/**
 * @var $this CompanyController
 * @var $model Company
 * @var $carModel Car
 * @var $priceList Price
 */

$this->tabs = array(
    'list' => array('url' => Yii::app()->createUrl($model->type . '/list'), 'name' => 'Универсальные'),
    'update' => array('url' => '#', 'name' => 'Редактирование компании ' . $model->name),
);

$this->renderPartial('_form', array('model' => $model));

$this->renderPartial('price/_list', array('model' => $model, 'priceList' => $priceList));
$this->renderPartial('price/_form', array('model' => $model, 'priceList' => $priceList));
