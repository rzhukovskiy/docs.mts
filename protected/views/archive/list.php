<?php
/**
 * @var $this ArchiveController
 * @var $model Act
 */
if (Yii::app()->user->model->company->type == Company::COMPANY_TYPE) {
    $this->tabs = array(
        $model->companyType != Company::CARWASH_TYPE ? Company::CARWASH_TYPE : 'list' => array('url' => Yii::app()->createUrl('archive/' . Company::CARWASH_TYPE), 'name' => 'Мойка'),
        $model->companyType != Company::SERVICE_TYPE ? Company::SERVICE_TYPE : 'list' => array('url' => Yii::app()->createUrl('archive/' . Company::SERVICE_TYPE), 'name' => 'Сервис'),
        $model->companyType != Company::TIRES_TYPE ? Company::TIRES_TYPE : 'list' => array('url' => Yii::app()->createUrl('archive/' . Company::TIRES_TYPE), 'name' => 'Шиномонтаж'),
    );
} else {
    $this->tabs = array(
        'list' => array('url' => Yii::app()->createUrl('archive/' . Yii::app()->user->model->company->type), 'name' => 'Акты'),
    );
}
?>

    <style>
        .ui-datepicker-calendar {
            display: none;
        }
    </style>
    <div class="contenttitle radiusbottom0">
        <h2 class="table"><span>Услуги</span></h2>
    </div>

<?php
$this->renderPartial('_selector', array('model' => $model));
$this->renderPartial('_list', array('model' => $model));
