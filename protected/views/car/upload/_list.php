<?php
/**
 * @var $this CarController
 * @var $model Car
 */
$this->widget('zii.widgets.grid.CGridView', array(
    'afterAjaxUpdate' => 'function(id, data){searchHighlight(id, data);}',
    'id' => 'car-grid',
    'htmlOptions' => array('class' => 'my-grid'),
    'itemsCssClass' => 'stdtable grid',
    'pagerCssClass' => 'dataTables_paginate paging_full_numbers',
    'dataProvider' => new CArrayDataProvider($model, ['pagination' => false]),
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
            'htmlOptions' => array('style' => 'width: 100px; text-align:center;'),
            'value' => '$data->company->name',
        ),
        array(
            'name' => 'mark_id',
            'htmlOptions' => array('style' => 'width: 100px; text-align:center;'),
            'value' => '$data->mark->name',
        ),
        array(
            'name' => 'number',
            'htmlOptions' => array('style' => 'width: 100px; text-align:center;'),
        ),
        array(
            'name' => 'type_id',
            'htmlOptions' => array('style' => 'width: 100px; text-align:center;'),
            'value' => 'isset($data->type->name) ? $data->type->name : ""',
        ),
        array(
            'class' => 'CButtonColumn',
            'template' => '{update}{delete}',
            'header' => '',
            'updateButtonUrl' => 'Yii::app()->createUrl("/car/update", array("id" => $data->id))',
            'deleteButtonUrl' => 'Yii::app()->createUrl("/car/delete", array("id" => $data->id))',
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