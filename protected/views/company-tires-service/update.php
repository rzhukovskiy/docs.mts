<?php
/**
 * @var $this CompanyController
 * @var $model CompanyTiresService
 * @var $typeList Type
 * @var $serviceList TiresService[]
 */

$this->tabs = array(
    'list' => array('url' => Yii::app()->request->urlReferrer, 'name' => 'Назад'),
    'updatePrice' => array('url' => '#', 'name' => 'Редактирование цены на ' . $model->tiresService->description),
);
?>
    <div class="contenttitle radiusbottom0">
        <h2 class="table"><span>Редактировать прайс</span></h2>
    </div>
<?php
$this->renderPartial('/company-tires-service/_form', array(
    'model' => $model,
    'typeList' => $typeList,
    'serviceList' => $serviceList,
));