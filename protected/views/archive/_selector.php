
<?php
/**
 * @var $this ArchiveController
 * @var $model Act
 * @var $form CActiveForm
 */
$form = $this->beginWidget('CActiveForm', array(
    'method' => 'get',
    'action' => Yii::app()->createUrl("/archive/$model->companyType"),
    'id' => 'action-form',
    'errorMessageCssClass' => 'help-inline',
    'htmlOptions' => array('class'=>'stdform', 'novalidate'=>'novalidate'),
));
?>
<table cellspacing="0" cellpadding="0" border="0" class="stdtable">
    <thead>
        <tr>
            <th style="width: 250px">
                <?=CHtml::label('Выбор периода', '')?>
                <?=$form->textField($model, 'month')?>
                <?=CHtml::submitButton('Показать', array('class' => 'submit radius2 date-send', 'style' => 'display: none; opacity: 1;')); ?>
            </th>
            <th>&nbsp;</th>
        </tr>
        <tr class="header">
            <td colspan="3">&nbsp;</td>
        </tr>
    </thead>
</table>
<?php
$this->endWidget();
