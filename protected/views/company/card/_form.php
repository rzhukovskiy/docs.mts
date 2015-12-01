<div class="contenttitle radiusbottom0">
    <h2 class="table"><span>Добавить карту</span></h2>
</div>
<?php
/**
 * @var $this CardController
 * @var $form CActiveForm
 * @var $model Card
 */
$form = $this->beginWidget('CActiveForm', array(
        'id' => 'action-form',
        'action' => array($model->isNewRecord ? Yii::app()->createUrl("/card/create") : Yii::app()->createUrl("/card/update", array("id" => $model->id))),
        'errorMessageCssClass' => 'help-inline',
        'htmlOptions' => array('class' => 'stdform'),
        'enableAjaxValidation' => true,
        'clientOptions' => array('validateOnSubmit' => true),
    ));
?>
<div class="row">
    <span class="field">
        <?=$form->labelEx($model, 'num'); ?>
        <?=$form->hiddenField($model, 'company_id'); ?>
        <?=$form->textField($model, 'num'); ?>
        <?=$form->error($model, 'num');?>
        <?=CHtml::hiddenField('returnUrl', Yii::app()->createUrl("/company/cards", array('id' => $model->company_id))) ?>

        <?=CHtml::submitButton($model->isNewRecord ? 'Добавить карту' : 'Сохранить', array('class' => 'submit radius2', 'style' => 'opacity: 1;')); ?>
    </span>
</div>
<?php $this->endWidget(); ?>
<!-- form -->
