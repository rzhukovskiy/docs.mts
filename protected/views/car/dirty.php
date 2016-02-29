<?php
/**
 * @var $this CarController
 * @var $model Car
 */

$this->tabs = array(
    'list' => array('url' => Yii::app()->createUrl('car/list'), 'name' => 'Машины'),
    'dirty' => array('url' => Yii::app()->createUrl('car/dirty'), 'name' => 'Немытые'),
);
$this->renderPartial('_dirty', array('model'=>$model));