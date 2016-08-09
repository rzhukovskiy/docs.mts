<div class="contenttitle radiusbottom0">
    <h2 class="table"><span>Карты</span></h2>
</div>
<?php
/**
 * @var $this CardController
 * @var $model Card
 */
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'card-grid',
    'htmlOptions' => array('class' => 'my-grid'),
    'itemsCssClass' => 'stdtable grid',
    'pagerCssClass' => 'dataTables_paginate paging_full_numbers',
    'filter' => $model,
    'dataProvider' => $model->search(),
    'emptyText' => '',
    'cssFile' => false,
    'template' => "{items}",
    'loadingCssClass' => false,
    'columns' => array(
        array(
            'header' => '№',
            'htmlOptions' => array('style' => 'width: 40px; text-align:center;'),
            'value' => '++$row',
        ),
        array(
            'name' => 'company_id',
            'htmlOptions' => array(),
            'value' => '$data->cardCompany->name',
            'filter' => false,
        ),
        array(
            'name' => 'number',
            'htmlOptions' => array(),
        ),
        array(
            'class' => 'CButtonColumn',
            'template' => '{delete}',
            'header' => '',
            'deleteButtonUrl' => 'Yii::app()->createUrl("/card/delete", array("id" => $data->id))',
            'buttons' => array(
                'delete' => array(
                    'label' => '',
                    'imageUrl' => false,
                    'options' => array('class' => 'delete')
                ),
            ),
        )
    ),
));
?>