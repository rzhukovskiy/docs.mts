<?php
/**
 * @var $this ImageController
 * @var $model Type
 */

$this->tabs = array(
    'list' => array('url' => Yii::app()->createUrl('type/list'), 'name' => 'Виды ТС'),
);

$this->renderPartial('_list', array('model'=>$model));