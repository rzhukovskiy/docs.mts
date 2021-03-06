<?php
/**
 * @var $this CompanyController
 * @var $model Company
 * @var $carModel Car
 * @var $priceList Price
 * @var $tiresServiceList;
 * @var $typeList;
 * @var $serviceList;
 * @var $carByTypes CActiveDataProvider;
 * @var $countCarsByType int;
 */

$this->tabs = array(
    'list' => array('url' => Yii::app()->createUrl('company/list'), 'name' => 'Компании'),
    'update' => array('url' => '#', 'name' => 'Редактирование компании ' . $model->name),
    'cards' => array('url' => Yii::app()->createUrl('company/cards', array('id' => $model->id)), 'name' => 'Карты'),
);

$this->renderPartial('_form', array('model' => $model));

$this->renderPartial('/price/_list', array(
    'model' => $model,
    'priceList' => $priceList,
    'title' => 'Редактировать прайс по мойке',
));
$this->renderPartial('/price/_form', array('model' => $priceList));
echo "<br />";

$this->renderPartial('/company-tires-service/_list', array(
    'model' => $model,
    'priceList' => $tiresServiceList,
    'title' => 'Редактировать прайс по шиномонтажу',
));
$this->renderPartial('/company-tires-service/_form', array(
    'company' => $model,
    'typeList' => $typeList,
    'serviceList' => $serviceList,
));
echo CHtml::tag('br');

$this->renderPartial('car/_form', array('model' => $carModel));

echo CHtml::tag('br');

$this->renderPartial('car/_types', array(
    'carByTypes' => $carByTypes,
    'countCarsByType' => $countCarsByType,
    'companyId' => $model->id,
));

echo CHtml::tag('br');

$this->renderPartial('car/_list', array(
    'model' => $carModel,
    'modelSearch' => $carSearch,
    'companyMarks' => $companyMarks,
    'companyTypes' => $companyTypes,
    'companyOnOff' => $companyOnOff,
    ));
