<?php
/**
 * @var $this StatController
 * @var $model Act
 */
$this->tabs['index'] = ['url' => Yii::app()->createUrl('stat/index', ['type' => $model->partner->type]), 'name' => 'Статистика'];
if (Yii::app()->user->checkAccess(User::ADMIN_ROLE))
    $this->tabs['months'] = ['url' => Yii::app()->createUrl('stat/months', ['type' => $model->partner->type, 'Act[partner_id]' => $model->partner_id]), 'name' => 'По месяцам'];
$this->tabs['days'] = ['url' => '#', 'name' => 'По дням'];
?>
    <div class="contenttitle radiusbottom0">
        <h2 class="table">
            <span>
                <?=Yii::app()->user->role == User::ADMIN_ROLE ? 'Статистика' : (Yii::app()->user->role == User::PARTNER_ROLE ? 'Доходы' : 'Расходы')?>
                <?=Yii::app()->user->checkAccess(User::ADMIN_ROLE) ? $model->partner->name : ''?> по дням
            </span>
        </h2>
    </div>
<?php
$this->renderPartial('_days', ['model' => $model, 'details' => true]);
