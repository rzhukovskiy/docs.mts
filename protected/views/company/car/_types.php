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
        'emptyText' => '',
        'template' => "{items}",
    ) );