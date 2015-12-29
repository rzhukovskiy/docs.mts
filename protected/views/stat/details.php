<?php
/**
 * @var $this StatController
 * @var $model Act
 */
$this->tabs = array(
    'index' => ['url' => Yii::app()->createUrl('stat/index'), 'name' => 'Статистика'],
    'details' => ['url' => '#', 'name' => 'По дням'],
);
?>
    <div class="contenttitle radiusbottom0">
        <h2 class="table"><span>Статистика <?=Yii::app()->user->checkAccess(User::ADMIN_ROLE) ? $model->company->name : ''?> по дням</span></h2>
    </div>
<?php
$this->renderPartial('_days', ['model' => $model, 'details' => true]);
