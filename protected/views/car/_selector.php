
<?php
/**
 * @var $this CarController
 * @var $model Act
 * @var $form CActiveForm
 */

$halfs = [
    '1е полугодие',
    '2е полугодие'
];
$quarters = [
    '1й квартал',
    '2й квартал',
    '3й квартал',
    '4й квартал',
];
$months = [
    'январь',
    'февраль',
    'март',
    'апрель',
    'май',
    'июнь',
    'июль',
    'август',
    'сентябрь',
    'октябрь',
    'ноябрь',
    'декабрь',
];

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
            <th style="text-align: left;">
                <?=CHtml::label('Выбор периода', '')?>
                <?=CHtml::dropDownList('period', 0, Act::$periodList, array('class' =>'select-period autoinput', 'style' => 'margin-right: 10px;'))?>
                <?=CHtml::dropDownList('year', 10, range(date('Y') - 10, date('Y')), ['class' => 'autoinput', 'style' => 'display:none'])?>
                <?=CHtml::dropDownList('half', '', $halfs, ['class' => 'autoinput', 'style' => 'display:none'])?>
                <?=CHtml::dropDownList('quarter', '', $quarters, ['class' => 'autoinput', 'style' => 'display:none'])?>
                <?=CHtml::dropDownList('month', '', $months, ['class' => 'autoinput', 'style' => 'display:none'])?>
                <?=$form->hiddenField($model, 'from_date', ['class' => 'from_date'])?>
                <?=$form->hiddenField($model, 'to_date', ['class' => 'to_date'])?>
                <?=CHtml::submitButton('Показать', array('class' => 'submit radius2 date-send', 'style' => 'opacity: 1;')); ?>
            </th>
        </tr>
        <tr class="header">
            <td colspan="2">&nbsp;</td>
        </tr>
    </thead>
</table>
<?php
$this->endWidget();
