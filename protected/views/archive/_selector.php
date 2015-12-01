
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
    <tr>
		<td style="width: 120px">Выбор месяца</td>
		<td style="width: 120px"><?=$form->textField($model, 'month')?></td>
		<td style="width: 120px"><?=CHtml::submitButton('Показать', array('class' => 'submit radius2', 'style' => 'opacity: 1;')); ?></td>
		<td>&nbsp;</td>
    </tr>
</table>
<?php
$this->endWidget();
