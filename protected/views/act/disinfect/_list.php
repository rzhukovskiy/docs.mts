<div class="contenttitle radiusbottom0">
    <h2 class="table"><span>Дезинфицированные машины</span></h2>
</div>

<?php
/**
 * @var $this ActController
 * @var $infectedCarList CActiveDataProvider
 */

$this->widget('ext.groupgridview.GroupGridView', array(
    'id' => 'car-grid',
    'htmlOptions' => array('class' => 'my-grid'),
    'itemsCssClass' => 'stdtable grid',
    'pagerCssClass' => 'dataTables_paginate paging_full_numbers',
    'dataProvider' => $infectedCarList,
    'emptyText' => '',
    'cssFile' => false,
    'template' => "{items}",
    'loadingCssClass' => false,
    'extraRowColumns' => array('company_id'),
    'extraRowExpression' => '$data->company->name . " - " . $data->company->address',
    'extraRowPos' => 'above',
    'columns' => array(
        array(
            'header' => '№',
            'htmlOptions' => array('style' => 'width: 40px; text-align:center;'),
            'value' => '++$row',
        ),
        array(
            'name' => 'mark_id',
            'htmlOptions' => array(),
            'value' => 'isset($data->mark) ? $data->mark->name : ""',
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
    ),
));
?>