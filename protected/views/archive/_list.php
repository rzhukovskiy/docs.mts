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
            'visible' => $model->companyType != Company::DISINFECTION_TYPE,
        ),
        array(
            'name' => 'client',
            'value' => '$data->client->name',
            'htmlOptions' => array('class' => 'client', 'style' => 'display: none'),
            'headerHtmlOptions' => array('style' => 'display:none'),
            'footerHtmlOptions' => array('style' => 'display:none'),
            'filterHtmlOptions' => array('style' => 'display:none'),
            'header' => 'Филиал',
            'filter' => CHtml::listData(Company::model()->findAll('parent_id = :parent_id', [':parent_id' => $model->client_id]), 'id', 'name'),
            'visible' => Yii::app()->user->model->company->children,
        ),
        array(
            'header' => 'Карта',
            'name' => 'card_id',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'value' => 'isset($data->card) ? $data->card->number : "error"',
            'cssClassExpression' => '$data->hasError("card") ? "error" : ""',
            'visible' => $model->companyType != Company::DISINFECTION_TYPE,
        ),
        array(
            'header' => 'Номер',
            'name' => 'number',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'cssClassExpression' => '$data->hasError("car") ? "error" : ""',
        ),
        array(
            'name' => 'extra_number',
            'htmlOptions' => array('style' => 'width: 80px; text-align:center;'),
            'cssClassExpression' => '$data->hasError("truck") ? "error" : ""',
            'visible' => Yii::app()->user->checkAccess(User::PARTNER_ROLE) || $model->client->is_split,
        ),
        array(
            'header' => 'Марка',
            'name' => 'mark_id',
            'htmlOptions' => array(),
            'value' => 'isset($data->mark) ? $data->mark->name : ""',
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
            'footer' => Yii::app()->user->checkAccess(User::PARTNER_ROLE) ? $model->totalField($provider, 'expense') : $model->totalField($provider, 'income'),
            'footerHtmlOptions' => array('style' => 'text-align:center;'),
            'cssClassExpression' => Yii::app()->user->checkAccess(User::PARTNER_ROLE) ? '$data->hasError("expense") ? "error" : ""' : '$data->hasError("income") ? "error" : ""',
        ),
        array(
            'header' => 'Город',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'value' => '$data->partner->address',
            'visible' => $model->showCompany && $model->companyType != Company::DISINFECTION_TYPE,
        ),
        array(
            'name' => 'check',
            'htmlOptions' => array('style' => 'text-align:center;'),
            'value' => '$data->check ? $data->check : ($data->hasError("check") ? "error" : "")',
            'cssClassExpression' => '$data->hasError("check") ? "error" : ""',
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
            'template' => '{view}',
            'header' => '',
            'cssClassExpression' => '$data->partner->type == Company::CARWASH_TYPE ? "hidden" : ""',
            'buttons' => array(
                'view' => array(
                    'label' => '',
                    'imageUrl' => false,
                    'url' => 'Yii::app()->createUrl("/archive/sign", array("id" => $data->id))',
                    'options' => array('class' => 'view')
                ),
            ),
            'visible' => $model->companyType == Company::SERVICE_TYPE || $model->companyType == Company::TIRES_TYPE,
        ),
    ),
));