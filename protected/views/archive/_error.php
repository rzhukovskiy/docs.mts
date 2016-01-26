<?php
/**
 * @var $this ActController
 * @var $model Act
 * @var $form CActiveForm
 */
$provider = $model->withErrors()->search();
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
            'value' => '$data->partner->name',
        ),
        array(
            'header' => 'Клиент',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'value' => '$data->client->name',
        ),
        array(
            'header' => 'Город',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'value' => '$data->partner->address',
        ),
        array(
            'name' => 'card_id',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'value' => '$data->card->number',
            'cssClassExpression' => 'isset($data->car->company_id) && $data->card->company_id != $data->car->company_id ? "error" : ""',
        ),
        array(
            'name' => 'mark_id',
            'htmlOptions' => array(),
            'value' => '$data->mark->name',
            'filter' => CHtml::listData(Mark::model()->findAll(array('order' => 'id')), 'id', 'name'),
        ),
        array(
            'name' => 'number',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'cssClassExpression' => 'Car::model()->find("number = :number" ,array(":number" => $data->number)) ? "" : "error"',
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
            'cssClassExpression' => '$data->expense ? "" : "error"',
        ),
        array(
            'header' => 'Приход',
            'name' => 'income',
            'value' => '$data->getFormattedField("income")',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'cssClassExpression' => '$data->income ? "" : "error"',
        ),
        array(
            'name' => 'check',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'value' => '$data->check ? $data->check : ($data->partner->type == Company::CARWASH_TYPE ? "error" : "")',
            'cssClassExpression' => '!$data->check && $data->partner->type != Company::CARWASH_TYPE ? "error" : ""',
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
            'template' => '{update}',
            'header' => '',
            'buttons' => array(
                'update' => array(
                    'label' => '',
                    'imageUrl' => false,
                    'url' => 'Yii::app()->createUrl("archive/update", array("id" => $data->id))',
                ),
            ),
        ),
    ),
));