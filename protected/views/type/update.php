<?php
/**
 * @var $this TypeController
 * @var $model Type
 */

$this->tabs = array(
    'type' => array('url' => Yii::app()->createUrl('type/list'), 'name' => 'Виды ТС'),
    'mark' => array('url' => Yii::app()->createUrl('mark/list'), 'name' => 'МаркиТС'),
    'update' => array('url' => '#', 'name' => 'Редактирование ' . $model->name),
);

$this->renderPartial('_form', array('model'=>$model));
