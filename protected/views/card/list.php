<?php
/**
 * @var $this CardController
 * @var $model Card
 */

$this->tabs = array(
    'list' => array('url' => Yii::app()->createUrl('card/list'), 'name' => 'Карты'),
);
$this->renderPartial('_list', array('model'=>$model));