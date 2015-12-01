<?php
/**
 * @var $this ActController
 * @var $model Act
 */
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

<div class="row scope example clearfix">
    <?=CHtml::label("", "", array('class' => 'scope_num'))?>
    <?=CHtml::textField('Scope[description][]', '', array('class' => 'smallinput', 'style' => 'float: left; margin-right: 20px; width: 500px;')); ?>
    <?=CHtml::numberField('Scope[amount][]', '', array('class' => 'smallinput', 'style' => 'float: left; margin-right: 20px; width: 50px;')); ?>
    <?=CHtml::textField('Scope[sum][]', '', array('class' => 'smallinput', 'style' => 'width: 60px;')); ?>
    <?=CHtml::hiddenField('Scope[id][]', ''); ?>
    <?=CHtml::button('+', array('class' => 'add_scope', 'title' => 'Добавить вид работ')); ?>
    <?=CHtml::button('-', array('class' => 'remove_scope', 'title' => 'Добавить вид работ')); ?>
</div>

<?php $num = 1; foreach ($model->scope as $scope) { ?>
    <div class="row scope existed clearfix">
        <?=CHtml::label("$num", "", array('class' => 'scope_num'))?>
        <?=CHtml::textField('Scope[description][]', $scope->description, array('class' => 'smallinput', 'style' => 'float: left; margin-right: 20px; width: 500px;')); ?>
        <?=CHtml::numberField('Scope[amount][]', $scope->amount, array('class' => 'smallinput', 'style' => 'float: left; margin-right: 20px; width: 50px;')); ?>
        <?=CHtml::textField('Scope[sum][]', $scope->sum, array('class' => 'smallinput', 'style' => 'width: 60px;')); ?>
        <?=CHtml::hiddenField('Scope[id][]', $scope->id); ?>
        <?=CHtml::button('-', array('class' => 'remove_scope', 'title' => 'Добавить вид работ')); ?>
    </div>
<?php $num++; } ?>

<div class="row scope clearfix">
    <?=CHtml::label(count($model->scope) + 1, "", array('class' => 'scope_num'))?>
    <?=CHtml::textField('Scope[description][]', '', array('class' => 'smallinput', 'style' => 'float: left; margin-right: 20px; width: 500px;')); ?>
    <?=CHtml::numberField('Scope[amount][]', '', array('class' => 'smallinput', 'style' => 'float: left; margin-right: 20px; width: 50px;')); ?>
    <?=CHtml::textField('Scope[sum][]', '', array('class' => 'smallinput', 'style' => 'width: 60px;')); ?>
    <?=CHtml::hiddenField('Scope[id][]', ''); ?>
    <?=CHtml::button('+', array('class' => 'add_scope', 'title' => 'Добавить вид работ')); ?>
    <?=CHtml::button('-', array('class' => 'remove_scope', 'title' => 'Добавить вид работ')); ?>
</div>