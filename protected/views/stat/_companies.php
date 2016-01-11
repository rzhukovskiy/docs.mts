<?php
/**
 * @var $this ActController
 * @var $model Act
 * @var $form CActiveForm
 */
$provider = $model->byCompanies()->search();
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
            'footer' => 'Итого',
            'footerHtmlOptions' => array('style' => 'text-align:center;'),
        ),
        array(
            'header' => $model->companyType == Company::CARWASH_TYPE ? 'Мойки' : ($model->companyType == Company::TIRES_TYPE ? 'Шиномонтаж' : 'Сервис'),
            'name' => 'company',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'value' => $model->showCompany ? '$data->client->name' : '$data->partner->name',
        ),
        array(
            'header' => 'Город',
            'name' => 'address',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'value' => $model->showCompany ? '$data->client->address' : '$data->partner->address',
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
            'value' => '$data->getFormattedFiled("expense")',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'footer' => $model->totalField($provider, 'expense'),
            'footerHtmlOptions' => array('style' => 'text-align:center;'),
        ),
        array(
            'header' => 'Прибыль',
            'name' => 'profit',
            'value' => '$data->getFormattedFiled("profit")',
            'htmlOptions' => [
                'style' => 'text-align:center;',
                'class' => 'total',
            ],
            'footer' => $model->totalField($provider, 'profit'),
            'footerHtmlOptions' => array('style' => 'text-align:center;'),
        ),
        array(
            'class' => 'CButtonColumn',
            'template' => '{history}',
            'header' => 'Детализация',
            'buttons' => array(
                'history' => array(
                    'label' => '',
                    'imageUrl' => false,
                    'url' => $model->showCompany
                        ? 'Yii::app()->createUrl("stat/months", array_merge($_GET, ["Act[company_id]" => $data->client_id]))'
                        : 'Yii::app()->createUrl("stat/months", array_merge($_GET, ["Act[company_id]" => $data-partner_id]))',
                    'options' => array('class' => 'calendar')
                ),
            ),
        )
    ),
));