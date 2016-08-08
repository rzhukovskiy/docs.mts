<?php
/**
 * @var $this CarController
 * @var $model Car
 * @var $modelSearch Car
 * @var $companyMarks Mark
 * @var $companyTypes Type
 * @var $companyOnOff array
 *
 */
?>
    <div class="contenttitle radiusbottom0">
        <h2 class="table"><span>Список ТС</span></h2>
    </div>
<?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'car-grid',
        'filter' => $modelSearch,
        'dataProvider' => $modelSearch->search(),
        'emptyText' => '',
        'htmlOptions' => array('class' => 'my-grid'),
        'itemsCssClass' => 'stdtable grid',
        'pagerCssClass' => 'dataTables_paginate paging_full_numbers',
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
                'name' => 'mark_id',
                'htmlOptions' => array('style' => 'width: 100px; text-align:center;'),
                'value' => 'isset($data->mark) ? $data->mark->name : ""',
                'filter' => CHtml::listData($companyMarks, 'mark.id', 'mark.name'),
            ),
            array(
                'name' => 'number',
                'htmlOptions' => array('style' => 'width: 100px; text-align:center;'),
            ),
            array(
                'name' => 'type_id',
                'value' => 'isset($data->type) ? $data->type->name : ""',
                'htmlOptions' => array('style' => 'text-align:center;'),
                'filter' => CHtml::listData($companyTypes, 'type.id', 'type.name'),
            ),
            array(
                'name' => 'is_infected',
                'value' => '$data->is_infected ? "Да" : "Нет"',
                'htmlOptions' => array('style' => 'width: 100px; text-align:center;'),
                'filter' => CHtml::dropDownList('Car[is_infected]', null, CHtml::listData($companyOnOff, 'id', 'title')),
            ),
            array(
                'class' => 'CButtonColumn',
                'template' => '{update}{delete}',
                'header' => '',
                'updateButtonUrl' => 'Yii::app()->createUrl("/car/update", array("id" => $data->id))',
                'deleteButtonUrl' => 'Yii::app()->createUrl("/car/delete", array("id" => $data->id))',
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
        )
    ));