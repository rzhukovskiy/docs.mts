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
    'dataProvider' => $model->byDays()->stat(),
    'emptyText' => '',
    'cssFile' => false,
    'template' => "{items}\n{pager}",
    'loadingCssClass' => false,
    'columns' => array(
        array(
            'header' => '№',
            'htmlOptions' => array('style' => 'width: 40px; text-align:left;'),
            'value' => '++$row',
            'footer' => 'Итого',
            'footerHtmlOptions' => array('style' => 'text-align:left;'),
        ),
        array(
            'name' => 'day',
            'htmlOptions' => array('style' => 'text-align:left;'),
            'value' => 'date("d", strtotime("$data->day 00:00:00")) . " " . StringNum::getMonthName(strtotime("$data->day 00:00:00"))[1] . " " . date("Y", strtotime("$data->day 00:00:00"))',
        ),
        array(
            'header' => 'Обслужено',
            'name' => 'amount',
            'htmlOptions' => array('style' => 'text-align:left;'),
            'footer' => $model->totalAmount(),
            'footerHtmlOptions' => array('style' => 'text-align:left;'),
        ),
        array(
            'header' => Yii::app()->user->role == User::MANAGER_ROLE ? 'Доход' : 'Расход',
            'name' => 'expense',
            'value' => 'number_format($data->expense)',
            'htmlOptions' => array('style' => 'text-align:left;'),
            'visible' => Yii::app()->user->checkAccess(User::MANAGER_ROLE),
            'footer' => number_format($model->totalExpense(true)),
            'footerHtmlOptions' => array('style' => 'text-align:left;'),
        ),
        array(
            'header' => Yii::app()->user->role == User::WATCHER_ROLE ? 'Расход' : 'Доход',
            'name' => 'income',
            'value' => 'number_format($data->income)',
            'htmlOptions' => array('style' => 'text-align:left;'),
            'visible' => Yii::app()->user->checkAccess(User::WATCHER_ROLE),
            'footer' => number_format($model->totalIncome(true)),
            'footerHtmlOptions' => array('style' => 'text-align:left;'),
        ),
        array(
            'header' => 'Прибыль',
            'name' => 'profit',
            'value' => 'number_format($data->profit)',
            'htmlOptions' => array('style' => 'text-align:left;'),
            'visible' => Yii::app()->user->checkAccess(User::ADMIN_ROLE),
            'footer' => number_format($model->totalProfit(true)),
            'footerHtmlOptions' => array('style' => 'text-align:left;'),
        ),
        array(
            'class' => 'CButtonColumn',
            'template' => '{history}',
            'header' => 'Детализация',
            'buttons' => array(
                'history' => array(
                    'label' => '',
                    'imageUrl' => false,
                    'url' => 'Yii::app()->createUrl("stat/details", array_merge($_GET, ["Act[day]" => $data->day]))',
                    'options' => array('class' => 'calendar')
                ),
            ),
        )
    ),
));