<?php
/**
 * @var $this CompanyController
 * @var $model Company
 */

$this->tabs = array(
    'list' => array('url' => Yii::app()->createUrl('company/list'), 'name' => 'Компании'),
);

$this->renderPartial('_form', array('model' => $model));
$this->renderPartial('_list', array('model' => $model));