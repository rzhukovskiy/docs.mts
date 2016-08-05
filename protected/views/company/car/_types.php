<?php
    /**
     * Created by PhpStorm.
     * User: ruslanzh
     * Date: 04/08/16
     * Time: 14:41
     */

    /**
     * @var $carByTypes CActiveDataProvider;
     */
?>
    <div class="contenttitle radiusbottom0">
        <h2 class="table"><span>Список типов ТС</span></h2>
    </div>
<?php
    $this->widget( 'zii.widgets.grid.CGridView', array(
        'dataProvider' => $carByTypes,
        'columns' => array(
            'cars_count:number:Кол-во',
            'type.name',
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