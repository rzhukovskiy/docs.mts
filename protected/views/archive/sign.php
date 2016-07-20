<?php
/**
 * @var $this ActController
 * @var $model Act
 */

$this->tabs = array(
    'list' => array('url' => Yii::app()->createUrl("archive/$model->companyType"), 'name' => 'Услуги'),
    'sign' => array('url' => '#', 'name' => 'Предварительный акт'),
);

$this->renderPartial("_sign", ['model'=>$model]);
