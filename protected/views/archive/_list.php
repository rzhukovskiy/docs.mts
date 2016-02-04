<?php
/**
 * @var $this ActController
 * @var $model Act
 * @var $form CActiveForm
 */
$provider = $model->search();
?>
    <script type="text/javascript">
        $(document).ready(function() {
            addHeaders({
                tableSelector: "#act-grid",
                footers: [
                    {
                        className: '.client',
                        title: 'Итого',
                        rowClass: 'total'
                    }
                ],
                headers: [
                    {
                        className: '.client',
                        rowClass: 'header'
                    }
                ]
            });
        });
    </script>
<?php

$gridWidget = $this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'act-grid',
    'htmlOptions' => array('class' => 'my-grid'),
    'itemsCssClass' => 'stdtable grid',
    'dataProvider' => $provider,
    'template' => "{items}",
    'columns' => array(
        array(
            'header' => '№',
            'htmlOptions' => array('style' => 'width: 40px; text-align:center;'),
            'value' => '++$row',
            'footer' => 'Всего',
        ),
        array(
            'header' => 'День',
            'name' => 'service_date',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'value' => 'date("d-m-Y", strtotime($data->service_date))',
        ),
        array(
            'header' => 'Карта',
            'name' => 'card_id',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'value' => '$data->card->number',
        ),
        array(
            'header' => 'Номер',
            'name' => 'number',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'cssClassExpression' => 'Car::model()->find("number = :number" ,array(":number" => $data->number)) ? "" : "error"',
        ),
        array(
            'header' => 'Марка',
            'name' => 'mark_id',
            'htmlOptions' => array(),
            'value' => '$data->mark->name',
            'filter' => CHtml::listData(Mark::model()->findAll(array('order' => 'id')), 'id', 'name'),
        ),
        array(
            'header' => 'Тип',
            'name' => 'type_id',
            'htmlOptions' => array(),
            'filter' => CHtml::listData(Type::model()->findAll(array('order' => 'id')), 'id', 'name'),
            'value' => '$data->type->name',
        ),
        array(
            'header' => 'Услуга',
            'name' => Yii::app()->user->checkAccess(User::PARTNER_ROLE) ? 'partner_service' : 'client_service',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'value' => Yii::app()->user->checkAccess(User::PARTNER_ROLE) ? 'Act::$fullList[$data->partner_service]' : 'Act::$fullList[$data->client_service]',
            'visible' => $model->companyType == Company::CARWASH_TYPE,
        ),
        array(
            'header' => 'Сумма',
            'name' => Yii::app()->user->checkAccess(User::PARTNER_ROLE) ? 'expense' : 'income',
            'value' => Yii::app()->user->checkAccess(User::PARTNER_ROLE) ? '$data->getFormattedField("expense")' : '$data->getFormattedField("income")',
            'htmlOptions' => array('style' => 'text-align:center;', 'class' => 'sum'),
            'cssClassExpression' => Yii::app()->user->checkAccess(User::PARTNER_ROLE) ? '$data->expense ? "" : "error"' : '$data->income ? "" : "error"',
            'footer' => Yii::app()->user->checkAccess(User::PARTNER_ROLE) ? $model->totalField($provider, 'expense') : $model->totalField($provider, 'income'),
            'footerHtmlOptions' => array('style' => 'text-align:center;'),
        ),
        array(
            'name' => 'city',
            'header' => 'Город',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'value' => '$data->partner->address',
            'visible' => $model->showCompany,
        ),
        array(
            'name' => 'check',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'visible' => $model->companyType == Company::CARWASH_TYPE,
        ),
        array(
            'name' => 'client',
            'value' => '$data->client->name',
            'header' => '',
            'headerHtmlOptions' => array('style' => 'display:none', 'class' => 'client'),
            'htmlOptions' => array('style' => 'display:none', 'class' => 'client'),
            'footerHtmlOptions' => array('style' => 'display:none', 'class' => 'client'),
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
            'cssClassExpression' => '$data->partner->type == Company::CARWASH_TYPE? "hidden" : ""',
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