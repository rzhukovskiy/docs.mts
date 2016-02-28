<?php
/**
 * @var $this UniversalController
 * @var $model Company
 */

$this->tabs = array(
    'list' => array('url' => Yii::app()->createUrl('company/list'), 'name' => 'Универсальные'),
);

$this->renderPartial('_form', array('model'=>$model));
$this->renderPartial('_list', array('model'=>$model));