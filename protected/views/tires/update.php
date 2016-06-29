<?php
/**
 * @var $this CompanyController
 * @var $model Company
 * @var $priceList Price
 * @var $typeList Type
 * @var $serviceList TiresService[]
 */

$this->tabs = array(
    'list' => array('url' => Yii::app()->createUrl($model->type . '/list'), 'name' => 'Шиномонтаж'),
    'update' => array('url' => '#', 'name' => 'Редактирование ' . $model->name),
);
$this->renderPartial('_form', array('model' => $model));
?>
    <div class="contenttitle radiusbottom0">
        <h2 class="table"><span>Редактировать прайс</span></h2>
    </div>
<?php

$this->renderPartial('/company-tires-service/_list', array('model' => $model, 'priceList' => $priceList));
$this->renderPartial('/company-tires-service/_form', array(
    'company' => $model,
    'typeList' => $typeList,
    'serviceList' => $serviceList,
    ));