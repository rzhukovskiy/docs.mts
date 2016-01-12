<?php
/**
 * @var $this StatController
 * @var $model Act
 */
$this->tabs['index'] = ['url' => Yii::app()->createUrl('statCompany/index', ['type' => $model->companyType]), 'name' => 'Статистика'];
if (Yii::app()->user->checkAccess(User::ADMIN_ROLE))
    $this->tabs['months'] = ['url' => Yii::app()->createUrl('statCompany/months', ['type' => $model->companyType, 'Act[client_id]' => $model->client_id]), 'name' => 'По месяцам'];
$this->tabs['days'] = ['url' => '#', 'name' => 'По дням'];
?>
    <div class="contenttitle radiusbottom0">
        <h2 class="table">
            <span>
                <?=Yii::app()->user->role == User::ADMIN_ROLE ? 'Статистика' : (Yii::app()->user->role == User::PARTNER_ROLE ? 'Доходы' : 'Расходы')?>
                <?=Yii::app()->user->checkAccess(User::ADMIN_ROLE) ? $model->client->name : ''?> по дням
            </span>
        </h2>
    </div>
<?php
$this->renderPartial('_days', ['model' => $model, 'details' => true]);
