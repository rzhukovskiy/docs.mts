<?php
/**
 * @var $this ActController
 * @var $model Act
 * @var $form CActiveForm
 */
$provider = $model->search();

if (Yii::app()->user->checkAccess(User::ADMIN_ROLE)) {
    $this->renderPartial('_selector', array('model' => $model));
}

$gridWidget = $this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'act-grid',
    'htmlOptions' => array('class' => 'my-grid'),
    'itemsCssClass' => 'stdtable grid',
    'filter' => $model,
    'dataProvider' => $provider,
    'template' => "{items}",
    'columns' => array(
        array(
            'header' => '№',
            'htmlOptions' => array('style' => 'width: 40px; text-align:center;'),
            'value' => '++$row',
            'footer' => 'Итого',
            'footerHtmlOptions' => array('style' => 'text-align:center;'),
        ),
        array(
            'name' => 'service_date',
            'htmlOptions' => array('style' => ' width: 70px; text-align:center;'),
            'value' => 'date("j", strtotime($data->service_date))',
            'filter' => CHtml::dropDownList('Act[day]',
                $model->day,
                range(1, date('t', strtotime("$model->month-$model->day"))),
                array('empty' => 'Все')),
        ),
        array(
            'header' => 'Партнер',
            'name' => 'partner',
            'value' => '$data->partner->name',
            'htmlOptions' => array('style' => 'width: 100px;'),
            'filter' => CHtml::dropDownList('Act[client_id]',
                $model->client_id,
                CHtml::listData(Company::model()->findAll('type = :type' , array(':type' => $model->companyType)),'id', 'name'),
                array('empty' => 'Все', 'style' => 'width: 80px;')),
        ),
        array(
            'name' => 'card_id',
            'htmlOptions' => array('style' => 'width: 60px;'),
            'value' => '$data->card->number',
        ),
        array(
            'name' => 'number',
            'htmlOptions' => array('style' => 'width: 80px; text-align:center;'),
            'cssClassExpression' => 'Car::model()->find("number = :number" ,array(":number" => $data->number)) ? "" : "error"',
        ),
        array(
            'name' => 'mark_id',
            'htmlOptions' => array('style' => 'width: 80px; text-align:center;'),
            'value' => '$data->mark->name',
            'filter' => false,
        ),
        array(
            'name' => 'type_id',
            'htmlOptions' => array(),
            'filter' => false,
            'value' => '$data->type->name',
            'footer' => count($provider->getData()) . ' ' . StringNum::getNumEnding(count($provider->getData()), array('машина', 'машины', 'машин')),
        ),
        array(
            'name' => 'partner_service',
            'htmlOptions' => array('style' => 'width: 80px; text-align:center;'),
            'value' => 'Act::$fullList[$data->partner_service]',
            'filter' => false,
            'visible' => $model->companyType == Company::CARWASH_TYPE,
        ),
        array(
            'header' => 'Сумма',
            'name' => 'expense',
            'value' => '$data->getFormattedField("expense")',
            'htmlOptions' => array('style' => 'width: 60px; text-align:center;'),
            'cssClassExpression' => '$data->expense ? "" : "error"',
            'footer' => $model->totalField($provider, 'expense'),
            'footerHtmlOptions' => array('style' => 'text-align:center;'),
            'filter' => false,
        ),
        array(
            'name' => 'check',
            'htmlOptions' => array('style' => 'width: 60px; text-align:center;'),
            'visible' => $model->companyType == Company::CARWASH_TYPE,
        ),
        array(
            'name' => 'check_image',
            'type' => 'raw',
            'value' => '!empty($data->check_image) ? '
                      . 'CHtml::link("image", "/files/checks/" . $data->check_image,'
                      . 'array("class"=>"preview")) : "no image"',
            'htmlOptions' => array('style' => 'width: 40px;'),
            'visible' => $model->companyType == Company::CARWASH_TYPE,
            'filter' => false,
        ),
        array(
            'class' => 'CButtonColumn',
            'template' => '{update}{delete}',
            'header' => '',
            'buttons' => array(
                'update' => array(
                    'label' => '',
                    'imageUrl' => false,
                    'url' => 'Yii::app()->createUrl("/act/update", array("id" => $data->id))',
                    'options' => array('class' => 'update')
                ),
                'delete' => array(
                    'label' => '',
                    'imageUrl' => false,
                    'url' => 'Yii::app()->createUrl("/act/delete", array("id" => $data->id))',
                    'options' => array('class' => 'delete')
                ),
            ),
        ),
    ),
));