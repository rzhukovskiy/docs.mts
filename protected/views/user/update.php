<?php
/**
 * @var $this UserController
 * @var $model User
 */
$this->tabs = array(
    Company::COMPANY_TYPE => array('url' => '/user/' . Company::COMPANY_TYPE, 'name' => 'Компании'),
    Company::CARWASH_TYPE => array('url' => '/user/' . Company::CARWASH_TYPE, 'name' => 'Мойки'),
    Company::SERVICE_TYPE => array('url' => '/user/' . Company::SERVICE_TYPE, 'name' => 'Сервисы'),
    Company::TIRES_TYPE   => array('url' => '/user/' . Company::TIRES_TYPE, 'name' => 'Шиномонтаж'),
    'update'              => array('url' => '#', 'name' => 'Редактирование пользователя ' . $model->name),
);

$this->renderPartial('_form', array('model'=>$model));
