<?php
/**
 * @var $this TypeController
 * @var $model Type
 */

$this->tabs = array(
    'list' => array('url' => Yii::app()->createUrl('type/list'), 'name' => 'Виды ТС'),
    'mark' => array('url' => Yii::app()->createUrl('mark/list'), 'name' => 'Марки ТС'),
);

$this->renderPartial('_form', array('model'=>$model));
$this->renderPartial('_list', array('model'=>$model));