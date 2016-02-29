<div class="contenttitle radiusbottom0">
    <h2 class="table"><span>Пользователи</span></h2>
</div>
<?php
/**
 * @var $this UserController
 * @var $model User
 */
$attributes = array_values(array_filter($model->attributes));
$model->unsetAttributes();
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
            'name' => 'email',
            'htmlOptions' => array(),
        ),
        array(
            'name' => 'company_id',
            'htmlOptions' => array(),
            'value' => 'isset($data->company->name) ? $data->company->name : ""',
        ),
        array(
            'class' => 'CButtonColumn',
            'template' => '{login}{update}{delete}',
            'header' => '',
            'updateButtonUrl' => 'Yii::app()->createUrl("/user/update", array("id" => $data->id))',
            'deleteButtonUrl' => 'Yii::app()->createUrl("/user/delete", array("id" => $data->id))',
            'buttons' => array(
                'login' => array(
                    'label' => '',
                    'imageUrl' => false,
                    'url' => 'Yii::app()->createUrl("user/login", array("id" => $data->id))',
                    'options' => array('class' => 'door')
                ),
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
?>