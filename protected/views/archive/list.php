<?php
/**
 * @var $this ArchiveController
 * @var $model Act
 */
if (Yii::app()->user->model->company->type == Company::COMPANY_TYPE) {
    foreach(Company::$listService as $service => $name) {
        //не показываем самих себя
        if ($service == Yii::app()->user->model->company->type) continue;
        $this->tabs[$model->companyType != $service ? $service : 'list'] = ['url' => Yii::app()->createUrl("archive/$service"), 'name' => $name];
    }
} else {
    $this->tabs = array(
        'list' => array('url' => Yii::app()->createUrl('archive/' . Yii::app()->user->model->company->type), 'name' => 'Акты'),
    );
}
?>

    <style>
        .ui-datepicker-calendar, .ui-datepicker .ui-datepicker-buttonpane button.ui-datepicker-current {
            display: none;
        }
    </style>
    <div class="contenttitle radiusbottom0">
        <h2 class="table"><span>Услуги</span></h2>
    </div>

<?php
$this->renderPartial('_selector', array('model' => $model));
$this->renderPartial('_list', array('model' => $model));
$this->renderPartial('_empty', array('model' => $company));
