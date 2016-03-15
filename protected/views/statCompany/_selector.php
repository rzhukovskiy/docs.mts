
<?php
/**
 * @var $this StatController
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

$ts1 = strtotime($model->from_date);
$ts2 = strtotime($model->to_date);

$year1 = date('Y', $ts1);
$year2 = date('Y', $ts2);

$month1 = date('m', $ts1);
$month2 = date('m', $ts2);

$diff = (($year2 - $year1) * 12) + ($month2 - $month1);
switch ($diff) {
    case 1:
        $period = 1;
        break;
    case 3:
        $period = 2;
        break;
    case 6:
        $period = 3;
        break;
    case 12:
        $period = 4;
        break;
    default:
        $period = 0;
}

$form = $this->beginWidget('CActiveForm', array(
    'method' => 'get',
    'action' => Yii::app()->createUrl("statCompany/{$this->action->id}", ['type' => $model->companyType, 'Act[client_id]' => $model->client_id]),
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
                <?=CHtml::dropDownList('period', $period, Act::$periodList, array('class' =>'select-period autoinput', 'style' => 'margin-right: 10px;'))?>
                <?=CHtml::dropDownList('year', 10, range(date('Y') - 10, date('Y')), ['class' => 'autoinput', 'style' => $diff && $diff <= 12 ? '' : 'display:none'])?>
                <?=CHtml::dropDownList('half', '', $halfs, ['class' => 'autoinput', 'style' => $diff == 6 ? '' : 'display:none'])?>
                <?=CHtml::dropDownList('quarter', '', $quarters, ['class' => 'autoinput', 'style' => $diff == 3 ? '' : 'display:none'])?>
                <?=CHtml::dropDownList('month', '', $months, ['class' => 'autoinput', 'style' => $diff == 1 ? '' : 'display:none'])?>
                <?=$form->hiddenField($model, 'from_date', ['class' => 'from_date'])?>
                <?=$form->hiddenField($model, 'to_date', ['class' => 'to_date'])?>
                <?php if(Yii::app()->user->model->company && count(Yii::app()->user->model->company->children)) { ?>
                    <?=CHtml::dropDownList('Act[client_id]',
                        $model->client_id,
                        CHtml::listData(Yii::app()->user->model->company->children, 'id', 'name'),
                        ['class' => 'autoinput', 'empty' => 'все']
                    )?>
                <?php } ?>
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
