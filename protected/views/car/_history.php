<?php
/**
 * @var $this ActController
 * @var $model Act
 * @var $form CActiveForm
 */
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
    'dataProvider' => $model->cars(),
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
            'value'=>'date("d-m-Y", strtotime($data->service_date))',
        ),
        array(
            'name' => 'card_id',
            'htmlOptions' => array('style' => ''),
            'value' => '$data->card->num',
        ),
        array(
            'name' => 'service',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'value' => ' Act::$shortList[$data->company_service]',
        ),
        array(
            'name' => 'city',
            'header' => 'Город',
            'htmlOptions' => array('style' => ''),
            'value' => '$data->company->address',
        ),
        array(
            'name' => 'company_id',
            'htmlOptions' => array('style' => ''),
            'value' => '$data->card->company->name',
            'visible' => Yii::app()->user->checkAccess(User::ADMIN_ROLE),
        ),
        array(
            'header' => 'Сумма',
            'name' => 'income',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'cssClassExpression' => '$data->income ? "" : "error"',
            'footerHtmlOptions' => array('style' => 'text-align:center;'),
        ),
    ),
));