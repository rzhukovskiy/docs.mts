<div class="contenttitle radiusbottom0">
    <h2 class="table"><span>Карты</span></h2>
</div>
<script type="text/javascript">
    function createHeaders() {
        addHeaders({
            tableSelector: "#card-grid",
            headers: [
                {
                    className: '.client',
                    rowClass: 'header'
                }
            ]
        });
    }

    $(document).bind('ready', function() {
        createHeaders();
    });
</script>
<?php
/**
 * @var $this CardController
 * @var $model Card
 */

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'card-grid',
    'htmlOptions' => array('class' => 'my-grid'),
    'itemsCssClass' => 'stdtable grid',
    'pagerCssClass' => 'dataTables_paginate paging_full_numbers',
    'filter' => Yii::app()->user->checkAccess(User::ADMIN_ROLE) ? $model : null,
    'dataProvider' => $model->search(),
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
            'htmlOptions' => array(),
            'value' => '$data->cardCompany->name',
            'filter' => CHtml::listData(Company::model()->findAll('type = :type', array(':type' => Company::COMPANY_TYPE)), 'id', 'name'),
        ),
        array(
            'name' => 'number',
            'htmlOptions' => array('style' => 'text-align:center;'),
        ),
    ),
));
?>