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
    'dataProvider' => $model->byCompanies()->stat(),
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
            'name' => 'company_id',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'value' => $model->showCompany ? '$data->card->cardCompany->name' : '$data->company->name',
        ),
        array(
            'header' => 'Город',
            'name' => 'address',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'value' => $model->showCompany ? '$data->card->cardCompany->address' : '$data->company->address',
        ),
        array(
            'header' => 'Обслужено',
            'name' => 'amount',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'footer' => $model->totalAmount(),
            'footerHtmlOptions' => array('style' => 'text-align:center;'),
        ),
        array(
            'header' => 'Расход',
            'name' => 'expense',
            'value' => 'number_format($data->expense, 0, ".", " ")',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'footer' => number_format($model->totalExpense(true), 0, ".", " "),
            'footerHtmlOptions' => array('style' => 'text-align:center;'),
        ),
        array(
            'header' => 'Прибыль',
            'name' => 'profit',
            'value' => 'number_format($data->profit, 0, ".", " ")',
            'htmlOptions' => [
                'style' => 'text-align:center;',
                'class' => 'total',
            ],
            'footer' => number_format($model->totalProfit(true), 0, ".", " "),
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
                        ? 'Yii::app()->createUrl("statCompany/months", array_merge($_GET, ["Act[company_id]" => $data->card->company_id]))'
                        : 'Yii::app()->createUrl("statCompany/months", array_merge($_GET, ["Act[company_id]" => $data->company_id]))',
                    'options' => array('class' => 'calendar')
                ),
            ),
        )
    ),
));