<?php
/**
 * @var $this StatController
 * @var $model Act
 */
$this->tabs = array(
    'index' => ['url' => Yii::app()->request->urlReferrer, 'name' => 'Статистика'],
    'details' => ['url' => '#', 'name' => 'Детализация'],
);
?>
    <div class="contenttitle radiusbottom0">
        <h2 class="table"><span>Статистика</span></h2>
    </div>
<?php
$this->renderPartial('_days', ['model' => $model, 'details' => true]);
