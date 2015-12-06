<?php
/**
 * @var $this CarController
 * @var $model Act
 */
$sumName = 'income';
?>
<div class="row">
    <?=CHtml::label("Состав работ:", "")?>
    <span class="field">&nbsp;</span>
</div>

<div class="row">
    <?=CHtml::label("№", "")?>
    <?=CHtml::label("Вид работ:", "", array('class' => 'compact', 'style' => 'float: left; margin-right: 20px; width: 500px;'))?>
    <?=CHtml::label("Количество:", "", array('class' => 'compact', 'style' => 'float: left; margin-right: 20px;'))?>
    <?=CHtml::label("Цена 1 ед.:", "", array('class' => 'compact', 'style' => 'float: left;'))?>
</div>

<?php $num = 1; foreach ($model->scope as $scope) { ?>
    <div class="row scope existed clearfix">
        <?=CHtml::label("$num", "", array('readonly' => 'readonly', 'class' => 'scope_num'))?>
        <?=CHtml::textField('Scope[description][]', $scope->description, array('readonly' => 'readonly', 'class' => 'smallinput', 'style' => 'float: left; margin-right: 20px; width: 500px;')); ?>
        <?=CHtml::numberField('Scope[amount][]', $scope->amount, array('readonly' => 'readonly', 'class' => 'smallinput', 'style' => 'float: left; margin-right: 20px; width: 50px;')); ?>
        <?=CHtml::textField("Scope[$sumName][]", $scope->$sumName, array('readonly' => 'readonly', 'class' => 'smallinput', 'style' => 'width: 60px;')); ?>
    </div>
<?php $num++; } ?>