<?php
/**
 * @var $this CompanyController
 * @var $form CActiveForm
 * @var $model Company
 * @var $priceList Price
 */
$form = $this->beginWidget('CActiveForm', array(
        'id' => 'action-form',
        'action' => array(Yii::app()->createUrl("/carwash/addPrice")),
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
            <?=$form->dropDownList($priceList, 'type_id', CHtml::listData(Type::model()->findAll(array('order' => 'id')), 'id', 'name'), ['style' => 'width: 195px']); ?>
        </td>
        <td>
            <?=$form->label($priceList, 'outside'); ?>
        </td>
        <td>
            <?=$form->textField($priceList, 'outside', ['style' => 'width: 50px']); ?>
        </td>
        <td>
            <?=$form->label($priceList, 'inside'); ?>
        </td>
        <td>
            <?=$form->textField($priceList, 'inside', ['style' => 'width: 50px']); ?>
        </td>
        <td>
            <?=$form->label($priceList, 'engine'); ?>
        </td>
        <td>
            <?=$form->textField($priceList, 'engine', ['style' => 'width: 50px']); ?>
        </td>
        <td>
            <?=$form->hiddenField($priceList, 'company_id'); ?>
            <?=CHtml::submitButton('Добавить', array('class' => 'submit radius2', 'style' => 'opacity: 1;')); ?>
        </td>
    <tr>
</table>
<?php $this->endWidget(); ?>
<!-- form -->
