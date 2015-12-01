<?php
/**
 * @var $this CarController
 * @var $model Car
 */

$this->tabs = array(
    'list' => array('url' => Yii::app()->createUrl('car/list'), 'name' => 'Машины'),
    'update' => array('url' => '#', 'name' => 'Редактирование машины ' . $model->id),
);

$this->renderPartial('_form', array('model'=>$model));
