<?php
/**
 * @var $this MarkController
 * @var $model Mark
 */

$this->tabs = array(
    'type' => array('url' => Yii::app()->createUrl('type/list'), 'name' => 'Виды ТС'),
    'list' => array('url' => Yii::app()->createUrl('mark/list'), 'name' => 'Марки ТС'),
);

$this->renderPartial('_form', array('model'=>$model));
$this->renderPartial('_list', array('model'=>$model));