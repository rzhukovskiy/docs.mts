<?php
/**
 * @var $this CompanyController
 * @var $form CActiveForm
 * @var $model Company
 * @var $priceList Price
 */
$form = $this->beginWidget('CActiveForm', array(
        'id' => 'action-form',
        'action' => array(Yii::app()->createUrl("/company/addPrice")),
        'errorMessageCssClass' => 'help-inline',
        'htmlOptions' => array('class' => 'stdform'),
        'enableAjaxValidation' => true,
        'clientOptions' => array('validateOnSubmit' => true),
    ));

?>
<table class="stdtable grid">
    <tr>
        <td>
            <?=$form->labelEx($priceList, 'type_id'); ?>
        </td>
        <td>
            <?=$form->dropDownList($priceList, 'type_id', CHtml::listData(Type::model()->findAll(array('order' => 'id')), 'id', 'name')); ?>
        </td>
        <td>
            <?=$form->labelEx($priceList, 'outside'); ?>
        </td>
        <td>
            <?=$form->textField($priceList, 'outside'); ?>
            <? if ($model->is_split) {
                echo $form->textField($priceList->extra, 'outside');
            } ?>
        </td>
        <td>
            <?=$form->labelEx($priceList, 'inside'); ?>
        </td>
        <td>
            <?=$form->textField($priceList, 'inside'); ?>
            <? if ($model->is_split) {
                echo $form->textField($priceList->extra, 'inside');
            } ?>
        </td>
        <td>
            <?=$form->hiddenField($priceList, 'company_id'); ?>
            <?=CHtml::submitButton('Добавить', array('class' => 'submit radius2', 'style' => 'opacity: 1;')); ?>
        </td>
    <tr>
</table>
<?php $this->endWidget(); ?>
<!-- form -->
