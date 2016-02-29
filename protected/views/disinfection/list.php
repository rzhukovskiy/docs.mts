<?php
/**
 * @var $this DisinfectionController
 * @var $model Company
 */

$this->tabs = array(
    'list' => array('url' => Yii::app()->createUrl('carwash/list'), 'name' => 'Дезинфекция'),
);

$this->renderPartial('_form', array('model'=>$model));
$this->renderPartial('_list', array('model'=>$model));