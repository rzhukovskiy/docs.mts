<?php
/**
 * @var $this CompanyController
 * @var $model Company
 * @var $priceList Price
 */

$this->tabs = array(
    'list' => array('url' => Yii::app()->createUrl($model->type . '/list'), 'name' => 'Шиномонтаж'),
    'update' => array('url' => '#', 'name' => 'Редактирование ' . $model->name),
);

$this->renderPartial('_form', array('model' => $model));