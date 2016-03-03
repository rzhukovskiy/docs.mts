<div class="contenttitle radiusbottom0">
    <h2 class="table"><span>Редактировать цену</span></h2>
</div>
<?php
/**
 * @var $this PriceController
 * @var $form CActiveForm
 * @var $model Price
 */
$form = $this->beginWidget('CActiveForm', array(
        'id' => 'action-form',
        'action' => array(Yii::app()->createUrl("/price/update", array("id" => $model->id))),
        'errorMessageCssClass' => 'help-inline',
        'htmlOptions' => array('class' => 'stdform'),
        'enableAjaxValidation' => true,
        'clientOptions' => array('validateOnSubmit' => true),
    ));
?>
<table class="stdtable grid">
    <tr>
        <td>
            <?=$form->label($model, 'type_id'); ?>
        </td>
        <td>
            <?=$form->label($model, 'outside'); ?>
        </td>
        <td>
            <?=$form->label($model, 'inside'); ?>
        </td>
        <td>
            <?=$form->label($model, 'disinfection'); ?>
        </td>
        <td>
        </td>
    <tr>
    <tr>
        <td>
            <?=$form->dropDownList($model, 'type_id', CHtml::listData(Type::model()->findAll(array('order' => 'id')), 'id', 'name')); ?>
        </td>
        <td>
            <?=$form->textField($model, 'outside'); ?>
            <?php if (false /*model->company->is_split*/) {
                echo $form->textField($model->extra, 'outside');
            } ?>
        </td>
        <td>
            <?=$form->textField($model, 'inside'); ?>
            <?php if (false /*model->company->is_split*/) {
                echo $form->textField($model->extra, 'inside');
            } ?>
        </td>
        <td>
            <?=$form->textField($model, 'disinfection'); ?>
            <?php if (false /*model->company->is_split*/) {
                echo $form->textField($model->extra, 'disinfection');
            } ?>
        </td>
        <td>
            <?=$form->hiddenField($model, 'company_id'); ?>
            <?=CHtml::hiddenField('returnUrl', $model->company->type == Company::CARWASH_TYPE ? Yii::app()->createUrl("/carwash/update", array('id' => $model->company_id)) : Yii::app()->createUrl("/company/update", array('id' => $model->company_id))) ?>
            <?=CHtml::submitButton('Сохранить', array('class' => 'submit radius2', 'style' => 'opacity: 1;')); ?>
        </td>
    <tr>
</table>
<?php $this->endWidget(); ?>
<!-- form -->
