<?php
/**
 * @var $this StatCompanyController
 * @var $model Act
 */

foreach(Company::$listService as $service => $name) {
    $this->tabs[$model->companyType != $service ? $service : 'index'] = ['url' => Yii::app()->createUrl('statCompany/index', ['type' => $service]), 'name' => $name];
}
$this->tabs[$model->companyType ? 'total' : 'index'] = ['url' => Yii::app()->createUrl('statCompany/total'), 'name' => 'Общее'];

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
    $this->renderPartial('_company_chart', ['model' => $model]);
} else {
    $this->renderPartial('_months', ['model' => $model]);
    $this->renderPartial('_month_chart', ['model' => $model]);
}
