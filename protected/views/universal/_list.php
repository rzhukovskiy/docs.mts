<?php
    /**
     * @var $this UniversalController
     * @var $model Company
     */
?>
<div class="contenttitle radiusbottom0">
    <h2 class="table"><span>Универсальные компании</span></h2>
</div>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
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
            'class' => 'CButtonColumn',
            'template' => '{update}{delete}',
            'header' => '',
            'updateButtonUrl' => 'Yii::app()->createUrl("/' . $model->type . '/update", array("id" => $data->id))',
            'deleteButtonUrl' => 'Yii::app()->createUrl("/' . $model->type . '/delete", array("id" => $data->id))',
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