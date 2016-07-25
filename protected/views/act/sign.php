<?php
/**
* @var $this ActController
* @var $model Act
*/

$this->tabs = array(
    'sign' => array('url' => '#', 'name' => 'Оформление акта'),
);
?>
<div class="row">
    <h3>
        Введите фамилию:
    </h3>
</div>

<div class= "row" id="wPaint" style="position:relative; width:100%; height:300px; background-color:#eee; margin: 20px 0px;">
</div>
<script type="text/javascript">
    var first = false;

    function saveSign() {
        var image = $('#wPaint').wPaint('image');
        if (first) {
            var data = {sign: image};
        } else {
            var data = {name: image};
        }

        $.ajax({
            type: 'POST',
            url: '/act/sign?id=' + <?= $model->id ?>,
            data: data,
            success: function (resp) {
                if (first) {
                    document.location.href = document.referrer;
                } else {
                    first = true;
                    $('#wPaint').wPaint('clear');
                    $('h3').text('Распишитесь:');
                }
            }
        });
    }

    // init wPaint
    $('#wPaint').wPaint({
        path: '/js/wpaint/',
        saveImg:     saveSign,
        bg:          '#fff',
        lineWidth:   '1',       // starting line width
        fillStyle:   '#fff', // starting fill style
        strokeStyle: '#3355aa'  // start stroke style
    });
</script>
<div class="row stdtable">
    <span class="field">
        <?=CHtml::button('Очистить', array('class' => 'submit radius2', 'onclick' => "$('#wPaint').wPaint('clear');")); ?>
        <?= CHtml::button('Далее', array('class' => 'submit radius2', 'style' => 'opacity: 1;', 'onclick' => "saveSign();")); ?>
    </span>
</div>