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
        <td rowspan="2">
            <?=$form->label($priceList, 'type_id'); ?>
        </td>
        <td rowspan="2">
            <?=$form->dropDownList($priceList, 'type_id', CHtml::listData(Type::model()->findAll(array('order' => 'id')), 'id', 'name')); ?>
        </td>
        <td>
            <?=$form->label($priceList, 'outside'); ?>
        </td>
        <td>
            <?=$form->textField($priceList, 'outside'); ?>
            <?php if (false /*$model->is_split*/) {
                echo $form->textField($priceList->extra, 'outside');
            } ?>
        </td>
        <td>
            <?=$form->label($priceList, 'inside'); ?>
        </td>
        <td>
            <?=$form->textField($priceList, 'inside'); ?>
            <?php if (false /*$model->is_split*/) {
                echo $form->textField($priceList->extra, 'inside');
            } ?>
        </td>
        <td rowspan="2">
            <?=$form->hiddenField($priceList, 'company_id'); ?>
            <?=CHtml::submitButton('Добавить', array('class' => 'submit radius2', 'style' => 'opacity: 1;')); ?>
        </td>
    </tr>
    <tr>
        <td>
            <?=$form->label($priceList, 'engine'); ?>
        </td>
        <td>
            <?=$form->textField($priceList, 'engine'); ?>
        </td>
        <td>
            <?=$form->label($priceList, 'disinfection'); ?>
        </td>
        <td>
            <?=$form->textField($priceList, 'disinfection'); ?>
        </td>
    </tr>
</table>
<?php $this->endWidget(); ?>
<!-- form -->
