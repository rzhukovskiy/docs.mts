<div class="contenttitle radiusbottom0">
    <h2 class="table"><span>Редактировать машину</span></h2>
</div>
<?php
/**
 * @var $this CarController
 * @var $form CActiveForm
 * @var $model Car
 */
$form = $this->beginWidget('CActiveForm', array(
        'id' => 'action-form',
        'action' => array($model->isNewRecord ? Yii::app()->createUrl("/car/create") : Yii::app()->createUrl("/car/update", array("id" => $model->id))),
        'errorMessageCssClass' => 'help-inline',
        'htmlOptions' => array('class' => 'stdform'),
        'enableAjaxValidation' => true,
        'clientOptions' => array('validateOnSubmit' => true),
    ));
?>
<table class="stdtable grid">
    <tr>
        <td>
            <?=$form->labelEx($model, 'company_id'); ?>
        </td>
        <td>
            <?=$form->dropDownList($model, 'company_id', CHtml::listData(Company::model()->findAll('type = :type' ,array(':type' => Company::COMPANY_TYPE)), 'id', 'name')); ?>
            <?=$form->error($model, 'company_id');?>
        </td>
    </tr>
    <tr>
        <td>
            <?=$form->labelEx($model, 'mark_id'); ?>
        </td>
        <td>
            <?=$form->dropDownList($model, 'mark_id', CHtml::listData(Mark::model()->findAll(array('order' => 'id')), 'id', 'name')); ?>
            <?=$form->error($model, 'mark_id');?>
        </td>
    </tr>
    <tr>
        <td style="width: 200px">
            <?=$form->labelEx($model, 'number'); ?>
        </td>
        <td>
            <?=$form->textField($model, 'number', array('style' => 'width: 39%; text-align:center;')); ?>
            <?=$form->error($model, 'number');?>
        </td>
    </tr>
    <tr>
        <td>
            <?=$form->labelEx($model, 'type_id'); ?>
        </td>
        <td>
            <?=$form->dropDownList($model, 'type_id', CHtml::listData(Type::model()->findAll(array('order' => 'id')), 'id', 'name')); ?>
            <?=$form->error($model, 'type_id');?>
        </td>
    </tr>
    <tr>
        <td></td>
        <td>
            <?=CHtml::hiddenField('returnUrl', Yii::app()->createUrl("/company/update", array('id' => $model->company_id))) ?>
            <?=$form->hiddenField($model, 'company_id'); ?>
            <?=CHtml::submitButton($model->isNewRecord ? 'Добавить машину' : 'Сохранить', array('class' => 'submit radius2', 'style' => 'opacity: 1;')); ?>
        </td>
    </tr>
</table>
<?php $this->endWidget(); ?>
<!-- form -->
