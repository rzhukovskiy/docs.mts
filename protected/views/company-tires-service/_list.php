<?php
    /**
     * @var $this TiresController
     * @var $model Company
     * @var $priceList CompanyTiresService
     * @var $title string|null
     */

    $viewTitle = empty($title) ? 'Редактировать прайс' : $title;
?>
<div class="contenttitle radiusbottom0">
    <h2 class="table"><span><?php echo $viewTitle; ?></span></h2>
</div>

<?php

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'service-grid',
    'htmlOptions' => array('class' => 'my-grid'),
    'itemsCssClass' => 'stdtable grid',
    'dataProvider' => $priceList->byPrice()->search(),
    'emptyText' => '',
    'cssFile' => false,
    'template' => "{items}",
    'loadingCssClass' => false,
    'columns' => array(
        array(
            'name' => 'type_id',
            'value' => '$data->samePrices',
            'htmlOptions' => array(),
        ),
        array(
            'name' => 'tires_service_id',
            'value' => '$data->tiresService->description',
            'htmlOptions' => array(),
        ),
        array(
            'name' => 'price',
            'htmlOptions' => array(),
        ),
        array(
            'class' => 'CButtonColumn',
            'template' => '{update}{delete}',
            'header' => '',
            'updateButtonUrl' => 'Yii::app()->createUrl("/tires/updatePrice", array("id" => $data->id))',
            'deleteButtonUrl' => 'Yii::app()->createUrl("/tires/deletePrice", array("id" => $data->id))',
            'buttons' => array(
                'update' => array(
                    'label' => '',
                    'imageUrl' => false,
                    'options' => array('class' => 'update')
                ),
                'delete' => array(
                    'label' => '',
                    'imageUrl' => false,
                    'options' => array('class' => 'delete')
                ),
            ),
        )
    ),
));
