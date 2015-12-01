<?php
/**
 * @var $this ActController
 * @var $model Act
 */

$this->tabs = array(
    'carwash' => array('url' => Yii::app()->createUrl('act/carwash'), 'name' => 'Акты'),
    'update' => array('url' => '#', 'name' => 'Редактирование акта мойки'),
);

$this->renderPartial('carwash/_full_form', array('model'=>$model));
