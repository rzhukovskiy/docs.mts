<?php
/**
 * @var $this UserController
 * @var $model User
 * @var $type string
 */
foreach(Company::$listService as $service => $name) {
    $this->tabs[$model->companyType != $service ? $service : 'list'] = ['url' => Yii::app()->createUrl("user/$service"), 'name' => $name];
}

$this->renderPartial('_form', array('model'=>$model));
$this->renderPartial('_list', array('model'=>$model));