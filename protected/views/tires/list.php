<?php
/**
 * @var $this CompanyController
 * @var $model Company
 */

$this->tabs = array(
    'list' => array('url' => Yii::app()->createUrl($model->type . '/list'), 'name' => 'Шиномонтаж'),
);

$this->renderPartial('_form', array('model'=>$model));
$this->renderPartial('_list', array('model'=>$model));