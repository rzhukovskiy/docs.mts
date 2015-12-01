<?php
/**
 * @var $this ActController
 * @var $model Act
 */

$this->tabs = array(
    'list' => array('url' => Yii::app()->createUrl('act/carwash'), 'name' => 'Акты моек'),
    'carwash' => array('url' => '#', 'name' => 'Скачивание актов'),
);

$this->renderPartial('carwash/_act_list', array('model'=>$model));
