<?php
    /**
     *
     * @var CompanyController $this
     * @var CActiveDataProvider $provider
     * @var Company $companyModel
     */

    $title = "ТС типа «" . CHtml::encode($typeModel->name) . "»";
?>
    <div class="contenttitle radiusbottom0">
        <h2 class="table"><span><?=$title?></span></h2>
    </div>
<?php
    $this->widget( 'zii.widgets.grid.CGridView', array(
        'dataProvider' => $provider,
        'cssFile' => false,
        'template' => "{items}\n{pager}",
        'loadingCssClass' => false,
        'htmlOptions' => array('class' => 'my-grid'),
        'itemsCssClass' => 'stdtable grid',
        'columns' => array(
            array(
                'header' => '№',
                'htmlOptions' => array('style' => 'width: 40px; text-align:right;'),
                'value' => '++$row',
            ),
            array(
                'name' => 'number',
                'htmlOptions' => array('style' => 'width: 120px; text-align:right;'),
            ),
            array(
                'name' => 'mark.name',
                'htmlOptions' => array('style' => 'text-align:center;'),
            ),
            array(
                'name' => 'is_infected',
                'value' => '$data->is_infected ? "да" : "нет" ',
            )
        ),
    ) );