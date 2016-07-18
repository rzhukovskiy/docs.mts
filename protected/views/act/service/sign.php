<?php
/**
 * @var $this ActController
 * @var $model Act
 */

$this->tabs = array(
    'list' => array('url' => Yii::app()->createUrl("act/$model->companyType", array('showCompany' => $model->showCompany)), 'name' => 'Акты'),
    'sign' => array('url' => '#', 'name' => 'Подпись'),
);

$this->renderPartial("service/_sign", ['model'=>$model]);
