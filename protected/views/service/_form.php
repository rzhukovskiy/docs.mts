<div class="contenttitle radiusbottom0">
    <h2 class="table"><span><?=$model->isNewRecord ? 'Создать сервис' : 'Редактировать сервис'; ?></span></h2>
</div>
<?php
/**
 * @var $this CompanyController
 * @var $form CActiveForm
 * @var $model Company
 */
$form = $this->beginWidget('CActiveForm', array(
        'id' => 'action-form',
        'action' => array($model->isNewRecord ? Yii::app()->createUrl("/$model->type/create") : Yii::app()->createUrl("/$model->type/update", array("id" => $model->id))),
        'errorMessageCssClass' => 'help-inline',
        'htmlOptions' => array('class' => 'stdform'),
        'enableAjaxValidation' => true,
        'clientOptions' => array('validateOnSubmit' => true),
    ));
?>
<div class="row">
    <?=$form->label($model, 'name'); ?>
    <span class="field">
        <?=$form->textField($model, 'name', array('class' => 'span5')); ?>
        <?=$form->error($model, 'name'); ?>
    </span>
</div>
<div class="row">
    <?=$form->label($model, 'address'); ?>
    <span class="field">
        <?=$form->textField($model, 'address', array('class' => 'span5')); ?>
        <?=$form->error($model, 'address'); ?>
    </span>
</div>
<div class="row">
    <span class="field">
        <?=CHtml::submitButton($model->isNewRecord ? 'Создать сервис' : 'Сохранить', array('class' => 'submit radius2', 'style' => 'opacity: 1;')); ?>
    </span>
</div>
<?php $this->endWidget(); ?>
<!-- form -->
