<?php
/**
 * @var $this CarController
 * @var $model Car
 */

$this->tabs['list'] = ['url' => Yii::app()->createUrl('car/list'), 'name' => 'Машины'];
if (Yii::app()->user->checkAccess(User::ADMIN_ROLE)) {
    $this->tabs['dirty']  = ['url' => Yii::app()->createUrl('car/dirty'), 'name' => 'Немытые'];
    $this->tabs['upload'] = ['url' => Yii::app()->createUrl('car/upload'), 'name' => 'Загрузить'];
}