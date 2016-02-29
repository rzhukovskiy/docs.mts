<?php
/**
 * @var $this ImageController
 * @var $model Type
 */

$this->tabs = array(
    'list' => array('url' => Yii::app()->createUrl('image/list'), 'name' => 'Типы ТС'),
    'update' => array('url' => '#', 'name' => 'Редактирование ' . $model->name),
);

$this->renderPartial('_form', array('model'=>$model));
