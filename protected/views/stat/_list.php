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
            'htmlOptions' => array('style' => 'width: 40px; text-align:center;'),
            'value' => '++$row',
            'footer' => 'Итого',
        ),
        array(
            'name' => 'day',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'value' => '$data->day',
        ),
        array(
            'header' => 'Приход',
            'name' => 'income',
            'htmlOptions' => array('style' => 'text-align:center;'),
        ),
        array(
            'header' => 'Расход',
            'name' => 'expense',
            'htmlOptions' => array('style' => 'text-align:center;'),
        ),
        array(
            'header' => 'Прибыль',
            'name' => 'profit',
            'htmlOptions' => array('style' => 'text-align:center;'),
        ),
    ),
));