<?php
/**
 * @var $this ArchiveController
 * @var $model Act
 */

$this->tabs = array(
    'error' => array('url' => Yii::app()->createUrl("archive/error"), 'name' => 'Ошибки'),
    'update' => array('url' => '#', 'name' => 'Редактирование акта'),
);

$this->renderPartial("_full_form", array('model'=>$model));
