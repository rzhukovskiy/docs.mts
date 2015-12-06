<div class="contenttitle radiusbottom0">
    <h2 class="table"><span>Добавить марку ТС</span></h2>
</div>
<?php
/**
 * @var $this ImageController
 * @var $form CActiveForm
 * @var $model Type
 */
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'action-form',
    'action' => Yii::app()->createUrl("/image/update", array("id" => $model->id)),
    'errorMessageCssClass' => 'help-inline',
    'htmlOptions' => array('enctype' => 'multipart/form-data', 'class'=>'stdform', 'novalidate'=>'novalidate'),
    'enableAjaxValidation' => true,
    'clientOptions' => array('validateOnSubmit' => true),
));
?>
<div class="row">
    <?php echo $form->labelEx($model, 'name'); ?>
    <span class="field">
        <?php echo $form->textField($model, 'name'); ?>
        <?php echo $form->error($model, 'name'); ?>
    </span>
</div>

<div class="row">
    <?=$form->labelEx($model, 'screen'); ?>
    <span class="field">
                <?=$form->fileField($model, 'screen'); ?>
                <?=$form->error($model, 'screen'); ?>
            </span>
</div>

<div class="row stdformbutton">
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', array('class' => 'submit radius2', 'style' => 'opacity: 1;')); ?>
</div>
<?php $this->endWidget(); ?>
<!-- form -->
