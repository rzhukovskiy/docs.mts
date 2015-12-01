<?php
/**
 * @var $this CarController
 * @var $model Car
 */

$this->tabs = array(
    'list' => array('url' => Yii::app()->createUrl('car/list'), 'name' => 'Машины'),
);
if (Yii::app()->user->checkAccess(User::ADMIN_ROLE)) {
    $this->renderPartial('_form', array('model' => $model));
}
$this->renderPartial('_list', array('model'=>$model));