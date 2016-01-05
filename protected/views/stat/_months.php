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
    'dataProvider' => $model->byMonths()->stat(),
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
            'name' => 'month',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'value' => 'StringNum::getMonthName(strtotime("{$data->month}-01 00:00:00"))[0] . date(" Y", strtotime("$data->month-01 00:00:00"))',
        ),
        array(
            'header' => 'Обслужено',
            'name' => 'amount',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'footer' => $model->totalAmount(),
            'footerHtmlOptions' => array('style' => 'text-align:center;'),
        ),
        array(
            'header' => Yii::app()->user->role == User::MANAGER_ROLE ? 'Доход' : 'Расход',
            'name' => 'expense',
            'value' => 'number_format($data->expense, 0, ".", " ")',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'visible' => Yii::app()->user->checkAccess(User::MANAGER_ROLE),
            'footer' => number_format($model->totalExpense(true), 0, ".", " "),
            'footerHtmlOptions' => array('style' => 'text-align:center;'),
        ),
        array(
            'header' => Yii::app()->user->role == User::WATCHER_ROLE ? 'Расход' : 'Доход',
            'name' => 'income',
            'value' => 'number_format($data->income, 0, ".", " ")',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'visible' => Yii::app()->user->checkAccess(User::WATCHER_ROLE),
            'footer' => number_format($model->totalIncome(true), 0, ".", " "),
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
            'visible' => Yii::app()->user->checkAccess(User::ADMIN_ROLE),
            'footer' => number_format($model->totalProfit(true), 0, ".", " "),
            'footerHtmlOptions' => [
                'style' => 'text-align:center;',
            ],
        ),
        array(
            'class' => 'CButtonColumn',
            'template' => '{history}',
            'header' => 'Детализация',
            'buttons' => array(
                'history' => array(
                    'label' => '',
                    'imageUrl' => false,
                    'url' => 'Yii::app()->createUrl("stat/days", array_merge($_GET, ["Act[period]" => 1, "Act[month]" => $data->month]))',
                    'options' => array('class' => 'calendar')
                ),
            ),
        )
    ),
));