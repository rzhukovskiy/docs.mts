<?php
/**
 * @var $this CompanyController
 * @var $model Company
 */

$this->tabs = array(
    'list' => array('url' => Yii::app()->createUrl('company/list'), 'name' => 'Компании'),
    'update' => array('url' => Yii::app()->createUrl('company/update', array('id' => $model->id)), 'name' => 'Редактирование компании ' . $model->name),
    'cards' => array('url' => '#', 'name' => 'Карты'),
);

$this->renderPartial('card/_form', array('model' => $cardModel));
$this->renderPartial('card/_list', array('model' => $cardModel));
