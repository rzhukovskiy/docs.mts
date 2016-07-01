<?php
/**
 * @var $this CompanyController
 * @var $form CActiveForm
 * @var $model Price
 */
if (!$model->isNewRecord) {
?>
    <div class="contenttitle radiusbottom0">
        <h2 class="table"><span>Редактировать цену</span></h2>
    </div>
<?php
}
$form = $this->beginWidget('CActiveForm', array(
        'id' => 'action-form',
        'action' => $model->isNewRecord ? [Yii::app()->createUrl("/company/addPrice")] : [Yii::app()->createUrl("/price/update", ['id' => $model->id])],
        'errorMessageCssClass' => 'help-inline',
        'htmlOptions' => array('class' => 'stdform'),
        'enableAjaxValidation' => true,
        'clientOptions' => array('validateOnSubmit' => true),
    ));

?>
<table class="stdtable grid">
    <tr>
        <td rowspan="2">
            <?=$form->label($model, 'type_id'); ?>
        </td>
        <td rowspan="2">
            <?=$form->dropDownList($model, 'type_id', CHtml::listData(Type::model()->findAll(array('order' => 'id')), 'id', 'name')); ?>
        </td>
        <td>
            <?=$form->label($model, 'outside'); ?>
        </td>
        <td>
            <?=$form->textField($model, 'outside'); ?>
            <?php if (false /*$model->is_split*/) {
                echo $form->textField($model->extra, 'outside');
            } ?>
        </td>
        <td>
            <?=$form->label($model, 'inside'); ?>
        </td>
        <td>
            <?=$form->textField($model, 'inside'); ?>
            <?php if (false /*$model->is_split*/) {
                echo $form->textField($model->extra, 'inside');
            } ?>
        </td>
        <td rowspan="2">
            <?=$form->hiddenField($model, 'company_id'); ?>
            <?=CHtml::hiddenField('returnUrl', Yii::app()->request->urlReferrer)?>
            <?=CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', array('class' => 'submit radius2', 'style' => 'opacity: 1;')); ?>
        </td>
    </tr>
    <tr>
        <td>
            <?=$form->label($model, 'engine'); ?>
        </td>
        <td>
            <?=$form->textField($model, 'engine'); ?>
        </td>
        <td>
            <?=$model->company->type !='carwash' ? $form->label($model, 'disinfection') : ''; ?>
        </td>
        <td>
            <?=$model->company->type !='carwash' ? $form->textField($model, 'disinfection') : ''; ?>
        </td>
    </tr>
</table>
<?php $this->endWidget(); ?>
<!-- form -->
