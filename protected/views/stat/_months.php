<?php
/**
 * @var $this ActController
 * @var $model Act
 * @var $form CActiveForm
 */
$provider = $model->byMonths()->search();
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
            'footerHtmlOptions' => array('style' => 'text-align:center;'),
        ),
        array(
            'name' => 'month',
            'htmlOptions' => array('style' => 'text-align:center;', 'class' => 'value_0'),
            'value' => 'StringNum::getMonthName(strtotime("{$data->month}-01 00:00:00"))[0] . date(" Y", strtotime("$data->month-01 00:00:00"))',
            'cssClassExpression' => $model->to_date
                ? '"month_" . date("n", strtotime("$data->month-01 00:00:00"))'
                : 'date("Y", strtotime("$data->month-01 00:00:00")) == date("Y", time() - 31 * 24 * 3600) ? "month_" . date("n", strtotime("$data->month-01 00:00:00")) : ""',
        ),
        array(
            'header' => 'Обслужено',
            'name' => 'amount',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'footer' => $model->totalField($provider, 'amount'),
            'footerHtmlOptions' => array('style' => 'text-align:center;'),
        ),
        array(
            'header' => Yii::app()->user->role == User::PARTNER_ROLE ? 'Доход' : 'Расход',
            'name' => 'expense',
            'value' => '$data->getFormattedField("expense")',
            'htmlOptions' => [
                'style' => 'text-align:center;',
                'class' => Yii::app()->user->checkAccess(User::ADMIN_ROLE) ? '' : 'value_2',
            ],
            'visible' => Yii::app()->user->checkAccess(User::PARTNER_ROLE),
            'footer' => $model->totalField($provider, 'expense'),
            'footerHtmlOptions' => array('style' => 'text-align:center;'),
        ),
        array(
            'header' => Yii::app()->user->role == User::CLIENT_ROLE ? 'Расход' : 'Доход',
            'name' => 'income',
            'value' => '$data->getFormattedField("income")',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'visible' => Yii::app()->user->checkAccess(User::CLIENT_ROLE),
            'footer' => $model->totalField($provider, 'income'),
            'footerHtmlOptions' => array('style' => 'text-align:center;'),
        ),
        array(
            'header' => 'Прибыль',
            'name' => 'profit',
            'value' => '$data->getFormattedField("profit")',
            'htmlOptions' => [
                'style' => 'text-align:center;',
                'class' => 'total value_2',
            ],
            'visible' => Yii::app()->user->checkAccess(User::ADMIN_ROLE),
            'footer' => $model->totalField($provider, 'profit'),
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
                    'url' => 'Yii::app()->createUrl("stat/days", ["type" => "' . $model->companyType . '", "Act[partner_id]" => ' . $model->partner_id . ', "Act[month]" => $data->month])',
                    'options' => array('class' => 'calendar')
                ),
            ),
        )
    ),
));