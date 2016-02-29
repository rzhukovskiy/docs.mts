<?php
/**
 * @var $this CarController
 * @var $form CActiveForm
 * @var $model Act
 */
$form = $this->beginWidget('CActiveForm', array(
        'id' => 'action-form',
        'errorMessageCssClass' => 'help-inline',
        'enableAjaxValidation' => true,
        'clientOptions' => array('validateOnSubmit' => true),
        'htmlOptions' => array('enctype' => 'multipart/form-data', 'class'=>'stdform', 'novalidate'=>'novalidate'),
    ));
$sumName = 'income';
?>
<div class="row">
    <?=CHtml::label("№", "", array('class' => 'total'))?>
    <?=CHtml::label("Вид работ:", "", array('class' => 'compact total', 'style' => 'float: left; margin-right: 20px; width: 500px;'))?>
    <?=CHtml::label("Количество:", "", array('class' => 'compact total', 'style' => 'float: left; margin-right: 10px;'))?>
    <?=CHtml::label("Цена 1 ед.:", "", array('class' => 'compact total', 'style' => 'float: left;'))?>
</div>

<?php $num = 1; foreach ($model->scope as $scope) { ?>
    <div class="row scope existed clearfix">
        <?=CHtml::label("$num", "", array('class' => 'scope_num total'))?>
        <?=CHtml::textField('Scope[description][]', $scope->description, array('readonly' => 'readonly', 'class' => 'smallinput', 'style' => 'float: left; margin-right: 20px; width: 500px;')); ?>
        <?=CHtml::numberField('Scope[amount][]', $scope->amount, array('readonly' => 'readonly', 'class' => 'smallinput', 'style' => 'float: left; margin-right: 20px; width: 50px;')); ?>
        <?=CHtml::textField("Scope[$sumName][]", number_format($scope->$sumName, 0, ".", " "), array('readonly' => 'readonly', 'class' => 'smallinput', 'style' => 'width: 60px;')); ?>
    </div>
<?php $num++; } ?>

<div class="row">
    <?=CHtml::label('Итого:', '', array('class' => 'total', 'style' => 'margin-left: 734px; width: 60px;'))?>
    <?=CHtml::textField($sumName, $model->getFormattedField($sumName), array('readonly' => 'readonly', 'class' => 'smallinput total', 'style' => 'margin-left: 20px; width: 60px; float: left;')); ?>
    <?=CHtml::link('Назад', Yii::app()->request->urlReferrer, array('class' => 'btn-info pull-right', 'style' => 'margin: 0 0 0 20px'))?>
</div>
<?php $this->endWidget(); ?>
<!-- form -->
