<div class="contenttitle radiusbottom0">
    <h2 class="table"><span>Компании</span></h2>
</div>
<?php
/**
 * @var $this CompanyController
 * @var $model Company
 */
$type = $model->type;
$attributes = array_values(array_filter($model->attributes));
$model->unsetAttributes();
$model->type = $type;
echo CHtml::hiddenField('query', CJSON::encode($attributes));
$this->widget('ext.jQueryHighlight.DJqueryHighlight', array(
    'selector' => '.my-grid',
    'words' => $attributes
));
$this->widget('zii.widgets.grid.CGridView', array(
    'afterAjaxUpdate' => 'function(id, data){searchHighlight(id, data);}',
    'id' => 'company-grid',
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
    'filter' => $model,
    'dataProvider' => $model->search(),
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
            'name' => 'name',
            'htmlOptions' => array(),
        ),
        array(
            'name' => 'address',
            'htmlOptions' => array(),
        ),
        array(
            'header' => 'Количество карт',
            'value' => 'count($data->cards) . " (" . $data->cardRange .")"',
        ),
        array(
            'header' => 'Количество машин',
            'value' => '$data->carCount',
        ),
        array(
            'header' => 'Количество прицепов',
            'value' => '$data->trailerCount',
        ),
        array(
            'class' => 'CButtonColumn',
            'template' => '{update}{delete}',
            'header' => '',
            'updateButtonUrl' => 'Yii::app()->createUrl("/company/update", array("id" => $data->id))',
            'deleteButtonUrl' => 'Yii::app()->createUrl("/company/delete", array("id" => $data->id))',
            'buttons' => array(
                'update' => array(
                    'label' => '',
                    'imageUrl' => false,
                    'options' => array('class' => 'update')
                ),
                'delete' => array(
                    'label' => '',
                    'imageUrl' => false,
                    'options' => array('class' => 'delete')
                ),
            ),
        )
    ),
));