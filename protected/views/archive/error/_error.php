<?php
/**
 * @var $this ActController
 * @var $model Act
 * @var $form CActiveForm
 */

$gridWidget = $this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'act-grid',
    'htmlOptions' => array('class' => 'my-grid'),
    'itemsCssClass' => 'stdtable grid',
    'pagerCssClass' => 'dataTables_paginate paging_full_numbers',
    'pager' => array(
        'header' => '',
        'maxButtonCount' => 9,
        'prevPageLabel' => 'Предыдущая',
        'nextPageLabel' => 'Следующая',
        'firstPageLabel' => 'Первая',
        'lastPageLabel' => 'Последняя',
        'htmlOptions' => array(
            'class' => '',
        )
    ),
    'dataProvider' => $provider,
    'emptyText' => '',
    'cssFile' => false,
    'template' => "{items}\n{pager}",
    'loadingCssClass' => false,
    'columns' => array(
        array(
            'header' => '№',
            'htmlOptions' => array('style' => 'width: 40px; text-align:center;'),
            'value' => '++$row',
        ),
        array(
            'name' => 'service_date',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'value' => 'date("d-m-Y", strtotime($data->service_date))',
        ),
        array(
            'header' => 'Партнер',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'value' => 'isset($data->partner) ? $data->partner->name : "error"',
            'cssClassExpression' => '!isset($data->partner) ? "error" : ""',
        ),
        array(
            'header' => 'Клиент',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'value' => 'isset($data->client) ? $data->client->name : "error"',
            'cssClassExpression' => '!isset($data->client) ? "error" : ""',
        ),
        array(
            'name' => 'card_id',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'value' => 'isset($data->card) ? $data->card->number : "error"',
            'cssClassExpression' => '$data->hasError("card") ? "error" : ""',
        ),
        array(
            'name' => 'mark_id',
            'htmlOptions' => array(),
            'value' => 'isset($data->mark) ? $data->mark->name : ""',
            'filter' => CHtml::listData(Mark::model()->findAll(array('order' => 'id')), 'id', 'name'),
        ),
        array(
            'name' => 'number',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'value' => '$data->number ? $data->number : "нет"',
            'cssClassExpression' => '$data->hasError("car") ? "error" : ""',
        ),
        array(
            'name' => 'extra_number',
            'htmlOptions' => array('style' => 'width: 80px; text-align:center;'),
            'value' => '$data->extra_number ? $data->extra_number : "нет"',
            'cssClassExpression' => '$data->hasError("truck") ? "error" : ""',
        ),
        array(
            'name' => 'type_id',
            'htmlOptions' => array(),
            'filter' => CHtml::listData(Type::model()->findAll(array('order' => 'id')), 'id', 'name'),
            'value' => '$data->type->name',
        ),
        array(
            'header' => 'Расход',
            'name' => 'expense',
            'value' => '$data->getFormattedField("expense")',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'cssClassExpression' => '$data->hasError("expense") ? "error" : ""',
        ),
        array(
            'header' => 'Приход',
            'name' => 'income',
            'value' => '$data->getFormattedField("income")',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'cssClassExpression' => '$data->hasError("income") ? "error" : ""',
        ),
        array(
            'header' => 'Город',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'value' => '$data->partner->address',
        ),
        array(
            'name' => 'check',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'value' => '$data->check ? $data->check : ($data->hasError("check") ? "error" : "")',
            'cssClassExpression' => '$data->hasError("check") ? "error" : ""',
        ),
        array(
            'name' => 'check_image',
            'type' => 'raw',
            'value' => '(!empty($data->check_image)) ? '
                      .'CHtml::link("image", "/files/checks/" . $data->check_image,'
                      .'array("class"=>"preview")) : "no image"',
            'htmlOptions' => array('style' => 'width: 40px;'),
        ),
        array(
            'class' => 'CButtonColumn',
            'template' => '{update}{delete}',
            'header' => '',
            'buttons' => array(
                'update' => array(
                    'label' => '',
                    'imageUrl' => false,
                    'url' => 'Yii::app()->createUrl("archive/update", array("id" => $data->id))',
                ),
                'delete' => array(
                    'label' => '',
                    'imageUrl' => false,
                    'url' => 'Yii::app()->createUrl("archive/fix", array("id" => $data->id))',
                ),
            ),
        ),
    ),
));