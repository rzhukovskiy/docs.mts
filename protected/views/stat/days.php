<?php
/**
 * @var $this StatController
 * @var $model Act
 */
$this->tabs['index'] = ['url' => Yii::app()->createUrl('stat/index'), 'name' => 'Статистика'];
if (Yii::app()->user->checkAccess(User::ADMIN_ROLE))
    $this->tabs['months'] = ['url' => Yii::app()->createUrl('stat/months', ['Act[company_id]' => $model->company_id]), 'name' => 'По месяцам'];
$this->tabs['days'] = ['url' => '#', 'name' => 'По дням'];
?>
    <div class="contenttitle radiusbottom0">
        <h2 class="table">
            <span>
                <?=Yii::app()->user->role == User::ADMIN_ROLE ? 'Статистика' : (Yii::app()->user->role == User::MANAGER_ROLE ? 'Доход' : 'Расход')?>
                <?=Yii::app()->user->checkAccess(User::ADMIN_ROLE) ? $model->company->name : ''?> по дням
            </span>
        </h2>
    </div>
<?php
$this->renderPartial('_days', ['model' => $model, 'details' => true]);
