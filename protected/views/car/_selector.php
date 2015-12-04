
<?php
/**
 * @var $this CarController
 * @var $model Act
 * @var $form CActiveForm
 */
$form = $this->beginWidget('CActiveForm', array(
    'method' => 'get',
    'action' => Yii::app()->createUrl("/car/{$this->action->id}", array('id' => $id)),
    'id' => 'action-form',
    'errorMessageCssClass' => 'help-inline',
    'htmlOptions' => array('class'=>'stdform', 'novalidate'=>'novalidate'),
));
?>
<table cellspacing="0" cellpadding="0" border="0" class="stdtable">
    <thead>
        <tr>
            <th style="width: 600px">
                <?=CHtml::label('Выбор периода', '')?>
                <?=$form->dropDownList($model, 'period', Act::$periodList, array('class' =>'select-period', 'style' => 'min-width:200px'))?>
                <?=$form->textField($model, 'month', array('class' => 'month-selector smallinput', 'style' => $model->period != 1 ? 'display:none' : ''))?>
                <?=CHtml::submitButton('Показать', array('class' => 'submit radius2', 'style' => 'opacity: 1;')); ?>
            </th>
        </tr>
        <tr class="header">
            <td colspan="2">&nbsp;</td>
        </tr>
    </thead>
</table>
<?php
$this->endWidget();
