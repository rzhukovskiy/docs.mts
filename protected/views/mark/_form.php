<div class="contenttitle radiusbottom0">
    <h2 class="table"><span>Добавить марку ТС</span></h2>
</div>
<?php
/**
 * @var $this TypeController
 * @var $form CActiveForm
 * @var $model Type
 */
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'action-form',
    'action' => array($model->isNewRecord ? Yii::app()->createUrl("/mark/create") : Yii::app()->createUrl("/mark/update", array("id" => $model->id))),
    'errorMessageCssClass' => 'help-inline',
    'htmlOptions' => array('class' => 'stdform'),
    'enableAjaxValidation' => true,
    'clientOptions' => array('validateOnSubmit' => true),
));
?>
<div class="row">
    <?php echo $form->label($model, 'name'); ?>
    <span class="field">
        <?php echo $form->textField($model, 'name'); ?>
        <?php echo $form->error($model, 'name'); ?>
    </span>
</div>
<div class="row stdformbutton">
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', array('class' => 'submit radius2', 'style' => 'opacity: 1;')); ?>
</div>
<?php $this->endWidget(); ?>
<!-- form -->
