<?php
/**
 * @var $this CarController
 * @var $model Car
 */
$company_id = $model->company_id;
$attributes = array_values(array_filter(isset($_GET['Car']) ? $_GET['Car'] : array()));
$model->unsetAttributes();
$model->company_id = $company_id;
echo CHtml::hiddenField('query', CJSON::encode($attributes));
$this->widget('ext.jQueryHighlight.DJqueryHighlight', array(
    'selector' => '.my-grid',
    'words' => $attributes
));
$this->widget('zii.widgets.grid.CGridView', array(
    'afterAjaxUpdate' => 'function(id, data){searchHighlight(id, data);}',
    'id' => 'car-grid',
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
            'name' => 'mark_id',
            'htmlOptions' => array('style' => 'width: 100px; text-align:center;'),
            'value' => 'isset($data->mark) ? $data->mark->name : ""',
        ),
        array(
            'name' => 'number',
            'htmlOptions' => array('style' => 'width: 100px; text-align:center;'),
        ),
        array(
            'name' => 'type_id',
            'htmlOptions' => array('style' => 'width: 100px; text-align:center;'),
            'value' => 'isset($data->type) ? $data->type->name : ""',
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