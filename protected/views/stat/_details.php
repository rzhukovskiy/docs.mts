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
    'dataProvider' => $model->search(),
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
        ),
        array(
            'name' => 'service_date',
            'htmlOptions' => array('style' => 'text-align:left;'),
            'value' => 'date("d", strtotime("$data->service_date")) . " " . StringNum::getMonthName(strtotime("$data->service_date"))[1] . " " . date("Y", strtotime("$data->service_date"))',
        ),
        array(
            'name' => 'card_id',
            'htmlOptions' => array('style' => 'text-align:left;'),
            'value' => '$data->card->num',
        ),
        array(
            'name' => 'number',
            'htmlOptions' => array('style' => 'text-align:left;'),
            'cssClassExpression' => 'Car::model()->find("number = :number" ,array(":number" => $data->number)) ? "" : "error"',
        ),
        array(
            'name' => 'mark_id',
            'htmlOptions' => array(),
            'value' => '$data->mark->name',
            'filter' => CHtml::listData(Mark::model()->findAll(array('order' => 'id')), 'id', 'name'),
        ),
        array(
            'name' => 'type_id',
            'htmlOptions' => array(),
            'filter' => CHtml::listData(Type::model()->findAll(array('order' => 'id')), 'id', 'name'),
            'value' => '$data->type->name',
        ),
        array(
            'name' => Yii::app()->user->checkAccess(User::MANAGER_ROLE) ? 'service' : 'company_service',
            'htmlOptions' => array('style' => 'text-align:left;'),
            'value' => Yii::app()->user->checkAccess(User::MANAGER_ROLE) ? 'Act::$fullList[$data->service]' : 'Act::$fullList[$data->company_service]',
            'visible' => $model->companyType == Company::CARWASH_TYPE,
        ),
        array(
            'header' => 'Сумма',
            'name' => 'income',
            'value' => 'number_format($data->income)',
            'htmlOptions' => array('style' => 'text-align:left;'),
            'cssClassExpression' => '$data->income ? "" : "error"',
            'footer' => number_format($model->totalIncome()),
            'footerHtmlOptions' => array('style' => 'text-align:left;'),
            'visible' => Yii::app()->user->role == User::WATCHER_ROLE
        ),
        array(
            'header' => 'Сумма',
            'name' => 'expense',
            'value' => 'number_format($data->expense)',
            'htmlOptions' => array('style' => 'text-align:left;'),
            'cssClassExpression' => '$data->expense ? "" : "error"',
            'footer' => number_format($model->totalExpense()),
            'footerHtmlOptions' => array('style' => 'text-align:left;'),
            'visible' => Yii::app()->user->checkAccess(User::MANAGER_ROLE)
        ),
        array(
            'name' => 'city',
            'header' => 'Город',
            'htmlOptions' => array('style' => 'text-align:left;'),
            'value' => '$data->company->address',
            'visible' => $model->showCompany,
        ),
        array(
            'name' => 'check',
            'htmlOptions' => array('style' => 'text-align:left;'),
            'visible' => $model->companyType == Company::CARWASH_TYPE,
        ),
        array(
            'name' => 'check_image',
            'type' => 'raw',
            'value' => '(!empty($data->check_image)) ? '
                      .'CHtml::link("image", "/files/checks/" . $data->check_image,'
                      .'array("class"=>"preview")) : "no image"',
            'htmlOptions' => array('style' => 'width: 40px;'),
            'visible' => $model->companyType == Company::CARWASH_TYPE,
        ),
        array(
            'class' => 'CButtonColumn',
            'template' => '{details}',
            'header' => '',
            'cssClassExpression' => '$data->company->type == Company::CARWASH_TYPE? "hidden" : ""',
            'buttons' => array(
                'details' => array(
                    'label' => '',
                    'imageUrl' => false,
                    'url' => 'Yii::app()->createUrl("car/details", array("id" => $data->id))',
                    'options' => array('class' => 'update show-act-details')
                ),
            ),
            'visible' => $model->companyType != Company::CARWASH_TYPE,
        ),
    ),
));