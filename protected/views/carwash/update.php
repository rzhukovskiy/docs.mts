<?php
/**
 * @var $this CompanyController
 * @var $model Company
 * @var $priceList Price
 */

$this->tabs = array(
    'list' => array('url' => Yii::app()->createUrl('carwash/list'), 'name' => 'Мойки'),
    'update' => array('url' => '#', 'name' => 'Редактирование мойки ' . $model->name),
);

$this->renderPartial('_form', array('model' => $model));
$this->renderPartial('/price/_short_list', array('model' => $model, 'priceList' => $priceList));
$this->renderPartial('/price/_short_form', array('model' => $priceList));