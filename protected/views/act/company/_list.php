<?php
/**
 * @var $this ActController
 * @var $model Act
 * @var $form CActiveForm
 */

if (Yii::app()->user->checkAccess(User::ADMIN_ROLE)) {
    $this->renderPartial('_selector', array('model' => $model));
?>
    <script type="text/javascript">
        function createHeaders() {
            addHeaders({
                tableSelector: "#act-grid",
                footers: [
                    {
                        className: '.parent',
                        title: 'Всего',
                        rowClass: 'main total'
                    },
                    {
                        className: '.client',
                        title: 'Итого',
                        rowClass: 'total'
                    },
                ],
                headers: [
                    {
                        className: '.parent',
                        rowClass: 'main header'
                    },
                    {
                        className: '.client',
                        rowClass: 'header'
                    }
                ]
            });
        }

        $(document).ready(function() {
            createHeaders();
        });
    </script>
<?php
}

$attributes = array_values(array_filter($model->attributes));
$model->unsetAttributes(['card_id', 'number', 'extra_number', 'check']);
echo CHtml::hiddenField('query', CJSON::encode($attributes));
$this->widget('ext.jQueryHighlight.DJqueryHighlight', array(
    'selector' => '.my-grid',
    'words' => $attributes
));

$provider = $model->search();

$gridWidget = $this->widget('zii.widgets.grid.CGridView', array(
    'afterAjaxUpdate' => 'function(id, data){searchHighlight(id, data);createHeaders();stick();}',
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
            'footer' => 'Всего',
            'footerHtmlOptions' => array('style' => 'text-align:center; font-size: 14px'),
        ),
        array(
            'name' => 'month',
            'htmlOptions' => array('style' => 'display:none'),
            'headerHtmlOptions' => array('style' => 'display:none'),
            'footerHtmlOptions' => array('style' => 'display:none'),
            'filterHtmlOptions' => array('style' => 'display:none'),
        ),
        array(
            'name' => 'service_date',
            'htmlOptions' => array('style' => ' width: 70px; text-align:center;'),
            'value' => 'date("j", strtotime($data->service_date))',
            'filter' => CHtml::dropDownList('Act[day]',
                $model->day,
                range(1, date('t', strtotime("$model->month-$model->day"))),
                array('empty' => 'Все')),
            'footer' => count($provider->getData()) . ' ' . StringNum::getNumEnding(count($provider->getData()), array('машина', 'машины', 'машин')),
            'visible' => $model->companyType != Company::DISINFECTION_TYPE,
        ),
        array(
            'class' => 'DataColumn',
            'evaluateHtmlOptions' => true,
            'header' => 'Клиент',
            'name' => 'client',
            'cssClassExpression' => '!isset($data->client) ? "error" : ""',
            'value' => 'isset($data->client) ? $data->client->name : "error"',
            'htmlOptions' => array('class' => '"client"', 'data-header' => 'isset($data->client) ? "{$data->client->name} - {$data->client->address}" : "error"'),
            'filter' => CHtml::dropDownList('Act[client_id]',
                $model->client_id,
                CHtml::listData(Company::model()->findAll('type = :type', array(':type' => Company::COMPANY_TYPE)), 'id', 'name'),
                array('empty' => 'Все', 'style' => 'width: 80px;')),
        ),
        array(
            'name' => 'mark_id',
            'htmlOptions' => array('style' => 'width: 80px; text-align:center;'),
            'value' => 'isset($data->mark) ? $data->mark->name : ""',
            'filter' => false,
        ),
        array(
            'name' => 'number',
            'htmlOptions' => array('style' => 'width: 80px; text-align:center;'),
            'cssClassExpression' => '$data->hasError("car") ? "error" : ""',
        ),
        array(
            'name' => 'extra_number',
            'htmlOptions' => array('style' => 'width: 80px; text-align:center;'),
            'cssClassExpression' => '$data->hasError("truck") ? "error" : ""',
        ),
        array(
            'name' => 'type_id',
            'htmlOptions' => array(),
            'filter' => false,
            'value' => '$data->type->name',
        ),
        array(
            'name' => 'card_id',
            'htmlOptions' => array('style' => 'width: 60px;'),
            'value' => 'isset($data->card) ? $data->card->number : "error"',
            'cssClassExpression' => '$data->hasError("card") ? "error" : ""',
            'visible' => $model->companyType != Company::DISINFECTION_TYPE,
        ),
        array(
            'name' => 'client_service',
            'htmlOptions' => array('style' => 'width: 80px; text-align:center;'),
            'value' => 'Act::$fullList[$data->client_service]',
            'filter' => false,
            'visible' => in_array($model->companyType,[Company::CARWASH_TYPE, Company::DISINFECTION_TYPE]),
        ),
        array(
            'header' => 'Сумма',
            'name' => 'income',
            'value' => '$data->getFormattedField("income")',
            'htmlOptions' => array('style' => 'width: 60px; text-align:center;', 'class' => 'sum'),
            'cssClassExpression' => '$data->hasError("income") ? "error" : ""',
            'footer' => $model->totalField($provider, 'income'),
            'footerHtmlOptions' => array('style' => 'text-align:center; font-size: 14px'),
            'filter' => false,
        ),
        array(
            'name' => 'check',
            'htmlOptions' => array('style' => 'width: 60px; text-align:center;'),
            'value' => '$data->check ? $data->check : ($data->hasError("check") ? "error" : "")',
            'cssClassExpression' => '$data->hasError("check") ? "error" : ""',
            'visible' => $model->companyType == Company::CARWASH_TYPE,
        ),
        array(
            'name' => 'check_image',
            'type' => 'raw',
            'value' => '!empty($data->check_image) ? '
                . 'CHtml::link("image", "/files/checks/" . $data->check_image,'
                . 'array("class"=>"preview")) : "no image"',
            'cssClassExpression' => '$data->hasError("check") ? "error" : ""',
            'htmlOptions' => array('style' => 'width: 40px;'),
            'visible' => $model->companyType == Company::CARWASH_TYPE,
            'filter' => false,
        ),
        array(
            'header' => '',
            'value' => 'isset($data->client->parent) ? $data->client->parent->name : ""',
            'htmlOptions' => array('style' => 'display:none', 'class' => 'parent'),
            'headerHtmlOptions' => array('style' => 'display:none'),
            'footerHtmlOptions' => array('style' => 'display:none'),
            'filterHtmlOptions' => array('style' => 'display:none'),
        ),
        array(
            'class' => 'CButtonColumn',
            'template' => $model->companyType == Company::CARWASH_TYPE ? '{update}{delete}' : '{view}{update}{delete}',
            'header' => '',
            'buttons' => array(
                'update' => array(
                    'label' => '',
                    'imageUrl' => false,
                    'url' => 'Yii::app()->createUrl("/act/update", ["id" => $data->id, "showCompany" => 1])',
                    'options' => array('class' => 'update')
                ),
                'delete' => array(
                    'label' => '',
                    'imageUrl' => false,
                    'url' => 'Yii::app()->createUrl("/act/delete", ["id" => $data->id])',
                    'options' => array('class' => 'delete')
                ),
                'view' => array(
                    'label' => '',
                    'imageUrl' => false,
                    'url' => 'Yii::app()->createUrl("/archive/sign", ["id" => $data->id, "showCompany" => 1])',
                    'options' => array('class' => 'view')
                ),
            ),
        ),
    ),
));