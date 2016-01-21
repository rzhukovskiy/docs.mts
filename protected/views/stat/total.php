<?php
/**
 * @var $this StatController
 * @var $model Act
 */
$this->tabs = [
    Company::CARWASH_TYPE == $model->companyType ? 'index' : Company::CARWASH_TYPE =>
        ['url' => Yii::app()->createUrl('stat/index', ['type' => Company::CARWASH_TYPE]), 'name' => 'Мойка'],
    Company::SERVICE_TYPE == $model->companyType ? 'index' : Company::SERVICE_TYPE =>
        ['url' => Yii::app()->createUrl('stat/index', ['type' => Company::SERVICE_TYPE]), 'name' => 'Сервис'],
    Company::TIRES_TYPE == $model->companyType ? 'index' : Company::TIRES_TYPE =>
        ['url' => Yii::app()->createUrl('stat/index', ['type' => Company::TIRES_TYPE]), 'name' => 'Шиномонтаж'],
    'total' => ['url' => '#', 'name' => 'Общее'],
];
?>
    <div class="contenttitle radiusbottom0">
        <h2 class="table">
            <span>
                <?=Yii::app()->user->role == User::ADMIN_ROLE ? 'Статистика' : (Yii::app()->user->role == User::PARTNER_ROLE ? 'Доходы' : 'Расходы')?>
            </span>
        </h2>
    </div>
<?php
$this->renderPartial('_selector', ['model' => $model]);
$this->renderPartial('_total', ['model' => $model]);
?>
    <div style="display:none">
        <?php
        $this->renderPartial('_months', ['model' => $model]);
        ?>
    </div>
<?php
$this->renderPartial('_month_chart', ['model' => $model]);