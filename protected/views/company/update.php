<?php
/**
 * @var $this CompanyController
 * @var $model Company
 * @var $carModel Car
 * @var $priceList Price
 */

$this->tabs = array(
    'list' => array('url' => Yii::app()->createUrl('company/list'), 'name' => 'Компании'),
    'update' => array('url' => '#', 'name' => 'Редактирование компании ' . $model->name),
    'cards' => array('url' => Yii::app()->createUrl('company/cards', array('id' => $model->id)), 'name' => 'Карты'),
);

$this->renderPartial('_form', array('model' => $model));

$this->renderPartial('/price/_list', array('model' => $model, 'priceList' => $priceList));
$this->renderPartial('/price/_form', array('model' => $priceList));
echo "<br />";

$this->renderPartial('/company-tires-service/_list', array('model' => $model, 'priceList' => $tiresServiceList));
$this->renderPartial('/company-tires-service/_form', array('model' => $model));
echo "<br />";

$this->renderPartial('car/_form', array('model' => $carModel));
$this->renderPartial('car/_list', array('model' => $carModel));
