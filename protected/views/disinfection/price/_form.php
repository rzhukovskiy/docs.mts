<?php
/**
 * @var $this DisinfectionController
 * @var $form CActiveForm
 * @var $model Company
 * @var $priceList Price
 */
$form = $this->beginWidget('CActiveForm', array(
        'id' => 'action-form',
        'action' => array(Yii::app()->createUrl("/$this->type/addPrice")),
        'errorMessageCssClass' => 'help-inline',
        'htmlOptions' => array('class' => 'stdform'),
        'enableAjaxValidation' => true,
        'clientOptions' => array('validateOnSubmit' => true),
    ));
?>
<table class="stdtable grid">
    <tr>
        <td>
            <?=$form->label($priceList, 'type_id'); ?>
        </td>
        <td>
            <?=$form->dropDownList($priceList, 'type_id', CHtml::listData(Type::model()->findAll(array('order' => 'id')), 'id', 'name')); ?>
        </td>
        <td>
            <?=$form->label($priceList, 'disinfection'); ?>
        </td>
        <td>
            <?=$form->textField($priceList, 'disinfection'); ?>
        </td>
        <td>
            <?=$form->label($priceList, 'additional'); ?>
        </td>
        <td>
            <?=$form->textField($priceList, 'additional'); ?>
        </td>
        <td>
            <?=$form->hiddenField($priceList, 'company_id'); ?>
            <?=CHtml::submitButton('Добавить', array('class' => 'submit radius2', 'style' => 'opacity: 1;')); ?>
        </td>
    <tr>
</table>
<?php $this->endWidget(); ?>
<!-- form -->
