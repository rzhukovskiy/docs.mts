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
    'filter' => $model,
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
            'name' => 'number',
            'htmlOptions' => array('style' => 'text-align:center;'),
        ),
        array(
            'name' => 'cardCompany',
            'value' => '$data->cardCompany->name',
        ),
    ),
));
?>