<?php
/**
 * @var $this CarController
 * @var $model Act
 */

$this->tabs = array(
    'list' => array('url' => Yii::app()->createUrl('car/list'), 'name' => 'Машины'),
    'history' => array('url' => '#', 'name' => 'История машины ' . $model->number),
);
?>
    <div class="contenttitle radiusbottom0">
        <h2 class="table"><span>Услуги</span></h2>
    </div>
<?php
$this->renderPartial('_selector', array('model'=>$model, 'id' => $id));
$this->renderPartial('_history', array('model'=>$model));
?>
