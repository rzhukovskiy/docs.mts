<?php
/**
 * @var $this StatCompanyController
 * @var $model Act
 */
$this->tabs = [
    Company::CARWASH_TYPE == $model->companyType ? 'index' : Company::CARWASH_TYPE =>
        ['url' => Yii::app()->createUrl('statCompany/index', ['type' => Company::CARWASH_TYPE]), 'name' => 'Мойка'],
    Company::SERVICE_TYPE == $model->companyType ? 'index' : Company::SERVICE_TYPE =>
        ['url' => Yii::app()->createUrl('statCompany/index', ['type' => Company::SERVICE_TYPE]), 'name' => 'Сервис'],
    Company::TIRES_TYPE == $model->companyType ? 'index' : Company::TIRES_TYPE =>
        ['url' => Yii::app()->createUrl('statCompany/index', ['type' => Company::TIRES_TYPE]), 'name' => 'Шиномонтаж'],
    'total' => ['url' => Yii::app()->createUrl('statCompany/total'), 'name' => 'Общее'],
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
if (Yii::app()->user->checkAccess(User::ADMIN_ROLE)) {
    $this->renderPartial('_companies', ['model' => $model]);
} else {
    $this->renderPartial('_months', ['model' => $model]);
}
