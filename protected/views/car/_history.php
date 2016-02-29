<?php
/**
 * @var $this ActController
 * @var $model Act
 * @var $form CActiveForm
 */
$provider = $model->search();
$gridWidget = $this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'history-grid',
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
    'dataProvider' => $model->search(),
    'emptyText' => '',
    'cssFile' => false,
    'template' => "{items}\n{pager}",
    'loadingCssClass' => false,
    'columns' => array(
        array(
            'header' => '№',
            'htmlOptions' => array('style' => 'width: 40px; text-align:center;'),
            'value' => '++$row',
            'footer' => 'Итого',
        ),
        array(
            'name' => 'service_date',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'value' => 'date("d", strtotime("$data->service_date")) . " " . StringNum::getMonthName(strtotime("$data->service_date"))[1] . " " . date("Y", strtotime("$data->service_date"))',
        ),
        array(
            'name' => 'card_id',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'value' => '$data->card->number',
        ),
        array(
            'name' => 'client_service',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'value' => ' Act::$shortList[$data->client_service]',
        ),
        array(
            'name' => 'city',
            'header' => 'Город',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'value' => '$data->partner->address',
        ),
        array(
            'header' => 'Сумма',
            'name' => 'income',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'value' => '$data->getFormattedField("income")',
            'cssClassExpression' => '$data->income ? "" : "error"',
            'footerHtmlOptions' => array('style' => 'text-align:center;'),
            'footer' => $model->totalField($provider, 'income'),
        ),
        array(
            'class' => 'CButtonColumn',
            'template' => '{details}',
            'header' => '',
            'cssClassExpression' => '$data->partner->type == Company::CARWASH_TYPE? "hidden" : ""',
            'buttons' => array(
                'details' => array(
                    'label' => '',
                    'imageUrl' => false,
                    'url' => 'Yii::app()->createUrl("car/details", array("id" => $data->id))',
                    'options' => array('class' => 'update show-act-details')
                ),
            ),
        ),
    ),
));