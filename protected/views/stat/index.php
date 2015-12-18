<?php
/**
 * @var $this StatController
 * @var $model Act
 */
if (Yii::app()->user->role == User::MANAGER_ROLE) {
    $this->tabs = array(
        'index' => ['url' => Yii::app()->createUrl('stat/index', ['type' => Yii::app()->user->model->company->type]), 'name' => 'Доходы'],
    );
} else {
    $this->tabs = array(
        Company::CARWASH_TYPE == $model->companyType ? 'index' : Company::CARWASH_TYPE =>
            array('url' => Yii::app()->createUrl('stat/index', ['type' => Company::CARWASH_TYPE]), 'name' => 'Мойка'),
        Company::SERVICE_TYPE == $model->companyType ? 'index' : Company::SERVICE_TYPE =>
            array('url' => Yii::app()->createUrl('stat/index', ['type' => Company::SERVICE_TYPE]), 'name' => 'Сервис'),
        Company::TIRES_TYPE == $model->companyType ? 'index' : Company::TIRES_TYPE =>
            array('url' => Yii::app()->createUrl('stat/index', ['type' => Company::TIRES_TYPE]), 'name' => 'Шиномонтаж'),
    );
}
?>
    <div class="contenttitle radiusbottom0">
        <h2 class="table"><span>Статистика</span></h2>
    </div>
<?php
$this->renderPartial('_selector', ['model' => $model]);
if (Yii::app()->user->checkAccess(User::ADMIN_ROLE)) {
    $this->renderPartial('_companies', ['model' => $model, 'details' => false]);
} else {
    $this->renderPartial('_months', ['model' => $model, 'details' => false]);
}
