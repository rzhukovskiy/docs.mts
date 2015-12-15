<?php
/**
 * @var $this StatController
 * @var $model Act
 */
$this->tabs = array(
    Company::CARWASH_TYPE == $model->companyType ? 'index' : Company::CARWASH_TYPE =>
        array('url' => Yii::app()->createUrl('stat/index', ['type' => Company::CARWASH_TYPE]), 'name' => 'Мойка'),
    Company::SERVICE_TYPE == $model->companyType ? 'index' : Company::SERVICE_TYPE =>
        array('url' => Yii::app()->createUrl('stat/index', ['type' => Company::SERVICE_TYPE]), 'name' => 'Сервис'),
    Company::TIRES_TYPE == $model->companyType ? 'index' : Company::TIRES_TYPE =>
        array('url' => Yii::app()->createUrl('stat/index', ['type' => Company::TIRES_TYPE]), 'name' => 'Шиномонтаж'),
);
?>
    <div class="contenttitle radiusbottom0">
        <h2 class="table"><span>Статистика</span></h2>
    </div>
<?php
$this->renderPartial('_selector', array('model' => $model));
$this->renderPartial('_list', array('model' => $model));
