<?php
    /**
     * @var $this CompanyController
     * @var $model Company
     * @var $priceList Price
     * @var $title null|string
     */
    $viewTitle = empty($title) ? 'Редактировать прайс' : $title;
?>
<div class="contenttitle radiusbottom0">
    <h2 class="table"><span><?php echo $viewTitle; ?></span></h2>
</div>
<?php

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'price-grid',
    'htmlOptions' => array('class' => 'my-grid'),
    'itemsCssClass' => 'stdtable grid',
    'dataProvider' => $priceList->search(),
    'emptyText' => '',
    'cssFile' => false,
    'template' => "{items}\n{pager}",
    'loadingCssClass' => false,
    'columns' => array(
        array(
            'name' => 'type_id',
            'value' => '$data->type->name',
            'htmlOptions' => array(),
        ),
        array(
            'name' => 'outside',
            'htmlOptions' => array(),
            'value' => '$data->outside . (isset($data->extra) ? " + " . $data->extra->outside : "")',
        ),
        array(
            'name' => 'inside',
            'htmlOptions' => array(),
            'value' => '$data->inside . (isset($data->extra) ? " + " . $data->extra->inside : "")',
        ),
        array(
            'name' => 'engine',
            'htmlOptions' => array(),
        ),
        array(
            'name' => 'disinfection',
            'htmlOptions' => array(),
        ),
        array(
            'name' => 'additional',
            'htmlOptions' => array(),
        ),
        array(
            'class' => 'CButtonColumn',
            'template' => '{update}{delete}',
            'header' => '',
            'updateButtonUrl' => 'Yii::app()->createUrl("/price/update", array("id" => $data->id))',
            'deleteButtonUrl' => 'Yii::app()->createUrl("/company/deletePrice", array("id" => $data->id))',
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