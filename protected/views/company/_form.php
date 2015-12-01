<div class="contenttitle radiusbottom0">
    <h2 class="table"><span><?=$model->isNewRecord ? 'Создать компанию' : 'Редактировать компанию'; ?></span></h2>
</div>
<?php
/**
 * @var $this CompanyController
 * @var $form CActiveForm
 * @var $model Company
 */
$form = $this->beginWidget('CActiveForm', array(
        'id' => 'action-form',
        'action' => array($model->isNewRecord ? Yii::app()->createUrl("/company/create") : Yii::app()->createUrl("/company/update", array("id" => $model->id))),
        'errorMessageCssClass' => 'help-inline',
        'htmlOptions' => array('class' => 'stdform'),
        'enableAjaxValidation' => true,
        'clientOptions' => array('validateOnSubmit' => true),
    ));
?>
<div class="row">
    <?=$form->labelEx($model, 'name'); ?>
    <span class="field">
                    <?=$form->textField($model, 'name', array('class' => 'span5')); ?>
                    <?=$form->error($model, 'name'); ?>
                </span>
</div>
<div class="row">
    <?=$form->labelEx($model, 'address'); ?>
    <span class="field">
                    <?=$form->textField($model, 'address', array('class' => 'span5')); ?>
                    <?=$form->error($model, 'address'); ?>
                </span>
</div>
<div class="row">
    <?=$form->labelEx($model, 'cardList'); ?>
    <span class="field">
                    <?=$form->textField($model, 'cardList', array('class' => 'span5')); ?>
                    <?=$form->error($model, 'cardList'); ?>
                </span>
</div>
<div class="row">
    <span class="field">
        Для актов
    </span>
</div>
<div class="row">
    <?=$form->labelEx($model, 'contact'); ?>
    <span class="field">
        <?=$form->textField($model, 'contact', array('class' => 'span5')); ?>
        <?=$form->error($model, 'contact'); ?>
    </span>
</div>
<div class="row">
    <?=$form->labelEx($model, 'contract'); ?>
    <span class="field">
        <?=$form->textField($model, 'contract', array('class' => 'span5')); ?>
        <?=$form->error($model, 'contract'); ?>
    </span>
</div>
<div class="row">
    <?=$form->labelEx($model, 'act_header'); ?>
    <span class="field">
        <?=$form->textArea($model, 'act_header', array('class' => 'span5')); ?>
        <?=$form->error($model, 'act_header'); ?>
    </span>
</div>
<div class="row">
                <span class="field">
                    <?=CHtml::submitButton($model->isNewRecord ? 'Создать компанию' : 'Сохранить', array('class' => 'submit radius2', 'style' => 'opacity: 1;')); ?>
                </span>
</div>
<?php $this->endWidget(); ?>
<!-- form -->
