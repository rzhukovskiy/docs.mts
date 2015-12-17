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
    'dataProvider' => $details ? $model->byDays()->stat() : $model->byCompanies()->stat(),
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
            'name' => 'day',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'value' => '$data->day',
            'visible' => $details,
        ),
        array(
            'name' => 'company_id',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'value' => '$data->company->name',
            'visible' => !$details && Yii::app()->user->checkAccess(User::ADMIN_ROLE),
        ),
        array(
            'header' => Yii::app()->user->role == User::MANAGER_ROLE ? 'Приход' : 'Расход',
            'name' => 'expense',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'visible' => Yii::app()->user->checkAccess(User::MANAGER_ROLE),
        ),
        array(
            'header' => Yii::app()->user->role == User::WATCHER_ROLE ? 'Расход' : 'Приход',
            'name' => 'income',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'visible' => Yii::app()->user->checkAccess(User::WATCHER_ROLE),
        ),
        array(
            'header' => 'Прибыль',
            'name' => 'profit',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'visible' => Yii::app()->user->checkAccess(User::ADMIN_ROLE),
        ),
        array(
            'class' => 'CButtonColumn',
            'template' => '{history}',
            'header' => 'Детализация',
            'buttons' => array(
                'history' => array(
                    'label' => '',
                    'imageUrl' => false,
                    'url' => 'Yii::app()->createUrl("stat/details", $_GET)',
                    'options' => array('class' => 'calendar')
                ),
            ),
            'visible' => !$details,
        )
    ),
));