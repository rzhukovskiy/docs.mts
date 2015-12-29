<?php
/**
 * @var $this StatController
 * @var $model Act
 */
$this->tabs = array(
    'index' => ['url' => Yii::app()->request->urlReferrer, 'name' => 'Статистика'],
    'months' => ['url' => '#', 'name' => 'По месяцам'],
);
?>
    <div class="contenttitle radiusbottom0">
        <h2 class="table"><span>Статистика <?=$model->company->name?> по месяцам</span></h2>
    </div>
<?php
$this->renderPartial('_months', ['model' => $model, 'details' => true]);
