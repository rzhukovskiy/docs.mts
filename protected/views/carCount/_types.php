<?php
    /**
     * Created by PhpStorm.
     * User: ruslanzh
     * Date: 04/08/16
     * Time: 14:41
     */

    /**
     * @var $carByTypes CActiveDataProvider;
     * @var $countCarsByType int
     * @var $companyId int
     */

?>
    <div class="contenttitle radiusbottom0">
        <h2 class="table"><span>Список типов ТС</span></h2>
    </div>
<?php
    $this->widget( 'zii.widgets.grid.CGridView', array(
        'dataProvider' => $carByTypes,
        'columns' => array(
            array(
                'header' => '№',
                'htmlOptions' => array('style' => 'width: 40px; text-align:center;'),
                'footer' => 'Всего:',
                'value' => '++$row',
            ),
            array(
                'name' => 'cars_count',
                'header' => 'Кол-во',
                'type' => 'number',
                'htmlOptions' => array('style' => 'width: 80px; text-align:right;'),
                'footer' => $countCarsByType,
                'footerHtmlOptions' => array('style' => 'text-align:right;'),
            ),
            'type.name',
            array(
                'class' => 'CButtonColumn',
                'template' => '{history}',
                'header' => 'Детализация',
                'buttons' => array(
                    'history' => array(
                        'label' => '',
                        'imageUrl' => false,
                        'url' => 'Yii::app()->createUrl("carCount/carsDetailedStatistic", array("type" => $data->type_id))',
                        'options' => array('class' => 'calendar')
                    ),
                ),
            ),
        ),
        'id' => 'car-types-grid',
        'htmlOptions' => array('class' => 'my-grid'),
        'itemsCssClass' => 'stdtable grid',
        'pagerCssClass' => 'dataTables_paginate paging_full_numbers',
        'emptyText' => '',
        'cssFile' => false,
        'template' => "{items}",
        'loadingCssClass' => false,
    ) );