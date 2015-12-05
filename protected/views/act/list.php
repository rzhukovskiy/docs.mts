<?php
/**
 * @var $this ActController
 * @var $model Act
 */
if (Yii::app()->user->checkAccess(User::ADMIN_ROLE)) {
    $this->tabs = array(
        $model->companyType != Company::CARWASH_TYPE || $model->showCompany ? Company::CARWASH_TYPE : 'list' => array('url' => Yii::app()->createUrl('act/carwash'), 'name' => 'Акты моек'),
        $model->companyType != Company::CARWASH_TYPE || !$model->showCompany ? Company::CARWASH_TYPE . '_company' : 'list' => array('url' => Yii::app()->createUrl('act/carwash', array('showCompany' => 1)), 'name' => 'Для компаний'),
        $model->companyType != Company::SERVICE_TYPE || $model->showCompany ? Company::SERVICE_TYPE : 'list' => array('url' => Yii::app()->createUrl('act/service'), 'name' => 'Акты сервисов'),
        $model->companyType != Company::SERVICE_TYPE || !$model->showCompany ? Company::SERVICE_TYPE . '_company' : 'list' => array('url' => Yii::app()->createUrl('act/service', array('showCompany' => 1)), 'name' => 'Для компаний'),
        $model->companyType != Company::TIRES_TYPE || $model->showCompany ? Company::TIRES_TYPE : 'list' => array('url' => Yii::app()->createUrl('act/tires'), 'name' => 'Акты шиномонтажа'),
        $model->companyType != Company::TIRES_TYPE || !$model->showCompany ? Company::TIRES_TYPE . '_company' : 'list' => array('url' => Yii::app()->createUrl('act/tires', array('showCompany' => 1)), 'name' => 'Для компаний'),
    );
} elseif (Yii::app()->user->model->company->type == Company::COMPANY_TYPE) {
    $this->tabs = array(
        $model->companyType != Company::CARWASH_TYPE ? Company::CARWASH_TYPE : 'list' => array('url' => Yii::app()->createUrl('act/' . Company::CARWASH_TYPE), 'name' => 'Акты моек'),
        $model->companyType != Company::SERVICE_TYPE ? Company::SERVICE_TYPE : 'list' => array('url' => Yii::app()->createUrl('act/' . Company::SERVICE_TYPE), 'name' => 'Акты сервисов'),
        $model->companyType != Company::TIRES_TYPE ? Company::TIRES_TYPE : 'list' => array('url' => Yii::app()->createUrl('act/' . Company::TIRES_TYPE), 'name' => 'Акты шиномонтажа'),
    );
} else {
    $this->tabs = array(
        'list' => array('url' => Yii::app()->createUrl('act/' . Yii::app()->user->model->company->type), 'name' => 'Акты'),
    );
}

$view = $model->showCompany ? 'company' : 'service';

if (!Yii::app()->user->checkAccess(User::ADMIN_ROLE) && Yii::app()->user->model->company->type == $model->companyType) {
    $this->renderPartial('service/_form', array('model'=>$model));
} else {
    echo '<style>.ui-datepicker-calendar, .ui-datepicker .ui-datepicker-buttonpane button.ui-datepicker-current {display: none;}</style>';
}
?>

<div class="contenttitle radiusbottom0">
    <h2 class="table"><span>Услуги</span></h2>
</div>

<?php
$this->renderPartial("$view/_list", array('model' => $model));
