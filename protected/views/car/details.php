<?php
/**
 * @var $this CarController
 * @var $model Act
 */

$this->tabs = array(
    'history' => array('url' => Yii::app()->request->urlReferrer, 'name' => 'Список'),
    'details' => array('url' => '#', 'name' => 'Состав работ'),
);
?>
    <div class="contenttitle radiusbottom0">
        <h2 class="table"><span>Услуги</span></h2>
    </div>
<?php
$this->renderPartial('_details', array('model' => $model));
?>
