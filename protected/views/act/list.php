<?php
/**
 * @var $this ActController
 * @var $model Act
 */
if (
    Yii::app()->user->checkAccess(User::ADMIN_ROLE)
    || Yii::app()->user->model->company->type == Company::UNIVERSAL_TYPE
) {
    foreach(Company::$listService as $service => $name) {
        if ( Yii::app()->user->checkAccess(User::ADMIN_ROLE) ||
            Yii::app()->user->model->company->carwash == $service ||
            Yii::app()->user->model->company->remont == $service ||
            Yii::app()->user->model->company->tires == $service ||
            Yii::app()->user->model->company->disinfection == $service){

            $this->tabs[$model->companyType != $service || $model->showCompany ? $service : 'list'] = [
                'url' => Yii::app()->createUrl("act/$service"),
                'name' => $name
            ];
            if (Yii::app()->user->checkAccess(User::ADMIN_ROLE)) {
                $this->tabs[$model->companyType != $service || !$model->showCompany ? $service . '_company' : 'list'] = [
                    'url' => Yii::app()->createUrl("act/$service", ['showCompany' => 1]),
                    'name' => 'Для компании'
                ];
            }
        }
    }
} else {
    $this->tabs = array(
        'list' => array('url' => Yii::app()->createUrl('act/' . Yii::app()->user->model->company->type), 'name' => 'Акты'),
    );
}

$view = $model->showCompany ? 'company' : 'service';

if (!Yii::app()->user->checkAccess(User::ADMIN_ROLE) && Yii::app()->user->model->company->type != Company::COMPANY_TYPE) {
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
