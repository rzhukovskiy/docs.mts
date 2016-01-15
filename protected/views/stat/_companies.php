<?php
/**
 * @var $this ActController
 * @var $model Act
 * @var $form CActiveForm
 */
$provider = $model->byCompanies()->search();
$gridWidget = $this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'act-grid',
    'htmlOptions' => array('class' => 'my-grid data-table'),
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
            'footer' => 'Итого',
            'footerHtmlOptions' => ['style' => 'text-align:center;'],
        ),
        array(
            'header' => Company::$listService[$model->companyType],
            'name' => 'company',
            'htmlOptions' => array('style' => 'text-align:center;', 'class' => 'value_0'),
            'value' => '$data->partner->name',
        ),
        array(
            'header' => 'Город',
            'name' => 'address',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'value' => '$data->partner->address',
        ),
        array(
            'header' => 'Обслужено',
            'name' => 'amount',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'footer' => $model->totalField($provider, 'amount'),
            'footerHtmlOptions' => array('style' => 'text-align:center;'),
        ),
        array(
            'header' => 'Расход',
            'name' => 'expense',
            'value' => '$data->getFormattedField("expense")',
            'htmlOptions' => array('style' => 'text-align:center;', 'class' => 'value_1'),
            'footer' => $model->totalField($provider, 'expense'),
            'footerHtmlOptions' => ['style' => 'text-align:center;'],
        ),
        array(
            'header' => 'Прибыль',
            'name' => 'profit',
            'value' => '$data->getFormattedField("profit")',
            'htmlOptions' => [
                'style' => 'text-align:center;',
                'class' => 'total value_2',
            ],
            'footer' => $model->totalField($provider, 'profit'),
            'footerHtmlOptions' => ['style' => 'text-align:center;'],
        ),
        array(
            'class' => 'CButtonColumn',
            'template' => '{history}',
            'header' => 'Детализация',
            'buttons' => array(
                'history' => array(
                    'label' => '',
                    'imageUrl' => false,
                    'url' => 'Yii::app()->createUrl("stat/months", array_merge($_GET, ["Act[partner_id]" => $data->partner_id]))',
                    'options' => array('class' => 'calendar')
                ),
            ),
        )
    ),
));