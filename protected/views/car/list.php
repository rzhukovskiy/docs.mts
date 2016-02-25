<?php
/**
 * @var $this CarController
 * @var $model Car
 */

$this->tabs['list'] = ['url' => Yii::app()->createUrl('car/list'), 'name' => 'Машины'];
if (Yii::app()->user->checkAccess(User::ADMIN_ROLE) || Yii::app()->user->model->company->is_infected) {
    $this->tabs['dirty'] = ['url' => Yii::app()->createUrl('car/dirty'), 'name' => 'Немытые'];
}

$this->renderPartial('_list', array('model'=>$model));