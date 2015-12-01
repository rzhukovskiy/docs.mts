<?php
/**
 * @var $this UserController
 * @var $model User
 * @var $type string
 */
$this->tabs = array(
    $model->companyType != Company::COMPANY_TYPE ? Company::COMPANY_TYPE : 'list' => array('url' => Yii::app()->createUrl('user/' . Company::COMPANY_TYPE), 'name' => 'Компании'),
    $model->companyType != Company::CARWASH_TYPE ? Company::CARWASH_TYPE : 'list' => array('url' => Yii::app()->createUrl('user/' . Company::CARWASH_TYPE), 'name' => 'Мойки'),
    $model->companyType != Company::SERVICE_TYPE ? Company::SERVICE_TYPE : 'list' => array('url' => Yii::app()->createUrl('user/' . Company::SERVICE_TYPE), 'name' => 'Сервисы'),
    $model->companyType != Company::TIRES_TYPE ? Company::TIRES_TYPE : 'list'   => array('url' => Yii::app()->createUrl('user/' . Company::TIRES_TYPE), 'name' => 'Шиномонтаж'),
);

$this->renderPartial('_form', array('model'=>$model));
$this->renderPartial('_list', array('model'=>$model));