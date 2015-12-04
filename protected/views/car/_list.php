<div class="contenttitle radiusbottom0">
    <h2 class="table"><span>Машины</span></h2>
</div>
<?php
/**
 * @var $this CarController
 * @var $model Car
 */
$this->renderPartial('_selector', array('model' => $model, 'id' => $model->id));

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'car-grid',
    'htmlOptions' => array('class' => 'my-grid'),
    'itemsCssClass' => 'stdtable grid',
    'pagerCssClass' => 'dataTables_paginate paging_full_numbers',
    'filter' => $model,
    'dataProvider' => $model->services(),
    'emptyText' => '',
    'cssFile' => false,
    'template' => "{items}",
    'loadingCssClass' => false,
    'columns' => array(
        array(
            'header' => '№',
            'htmlOptions' => array('style' => 'width: 40px; text-align:center;'),
            'value' => '++$row',
        ),
        array(
            'name' => 'company_id',
            'htmlOptions' => array('style' => 'width: 60px; text-align:center;'),
            'value' => '$data->company->name',
            'filter' => CHtml::listData(Company::model()->findAll('type = :type', array(':type' => Company::COMPANY_TYPE)), 'id', 'name'),
            'visible' => Yii::app()->user->checkAccess(User::ADMIN_ROLE),
        ),
        array(
            'name' => 'mark_id',
            'htmlOptions' => array(),
            'value' => '$data->mark->name',
            'filter' => '',
        ),
        array(
            'name' => 'number',
            'htmlOptions' => array(),
        ),
        array(
            'name' => 'type_id',
            'htmlOptions' => array(),
            'value' => 'isset($data->type->name) ? $data->type->name : ""',
            'filter' => '',
        ),
        array(
            'name' => 'service_count',
            'value' => '$data->service_count',
            'htmlOptions' => array(),
            'filter' => '',
        ),
        array(
            'class' => 'CButtonColumn',
            'template' => '{history}',
            'header' => '',
            'buttons' => array(
                'history' => array(
                    'label' => '',
                    'imageUrl' => false,
                    'url' => 'Yii::app()->createUrl("car/history", array("id" => $data->id))',
                    'options' => array('class' => 'calendar')
                ),
            ),
        )
    ),
));
?>