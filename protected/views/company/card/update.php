<?php
/**
 * @var $this CardController
 * @var $model Card
 */

$this->tabs = array(
    'list' => array('url' => Yii::app()->createUrl('card/list'), 'name' => 'Карты'),
    'update' => array('url' => '#', 'name' => 'Редактирование карты ' . $model->id),
);

$this->renderPartial('_form', array('model'=>$model));
