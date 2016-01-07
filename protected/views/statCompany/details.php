<?php
/**
 * @var $this StatController
 * @var $model Act
 */
$this->tabs['index'] = ['url' => Yii::app()->createUrl('statCompany/index', ['type' => $model->companyType]), 'name' => 'Статистика'];
if (Yii::app()->user->checkAccess(User::ADMIN_ROLE))
    $this->tabs['months'] = ['url' => Yii::app()->createUrl('statCompany/months', ['type' => $model->companyType, 'Act[company_id]' => $model->company_id]), 'name' => 'По месяцам'];
$this->tabs['days'] = ['url' => Yii::app()->createUrl('statCompany/days', ['type' => $model->companyType, 'Act[company_id]' => $model->company_id, 'Act[month]' => date("Y-m", strtotime("$model->day 00:00:00"))]), 'name' => 'По дням'];
$this->tabs['details'] = ['url' => '#', 'name' => 'Детализация'];
?>
    <div class="contenttitle radiusbottom0">
        <h2 class="table">
            <span>
                <?=Yii::app()->user->role == User::ADMIN_ROLE ? 'Статистика' : (Yii::app()->user->role == User::MANAGER_ROLE ? 'Доходы' : 'Расходы')?> за день
            </span>
        </h2>
    </div>
<?php
$this->renderPartial('_details', ['model' => $model]);
