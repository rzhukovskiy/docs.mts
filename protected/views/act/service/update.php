<?php
/**
 * @var $this ActController
 * @var $model Act
 */

$this->tabs = array(
    'list' => array('url' => Yii::app()->createUrl("act/$model->companyType", array('showCompany' => $model->showCompany)), 'name' => 'Акты'),
    'update' => array('url' => '#', 'name' => 'Редактирование акта'),
);

$this->renderPartial("service/_full_form", array('model'=>$model));
