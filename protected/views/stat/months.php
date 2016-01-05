<?php
/**
 * @var $this StatController
 * @var $model Act
 */
$this->tabs = [
    'index' => ['url' => Yii::app()->createUrl('stat/index', ['type' => $model->companyType]), 'name' => 'Статистика'],
    'months' => ['url' => '#', 'name' => 'По месяцам'],
];
?>
    <div class="contenttitle radiusbottom0">
        <h2 class="table"><span>Статистика <?=$model->company->name?> по месяцам</span></h2>
    </div>
<?php
$this->renderPartial('_selector', ['model' => $model]);
$this->renderPartial('_months', ['model' => $model]);
