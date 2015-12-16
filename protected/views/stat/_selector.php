
<?php
/**
 * @var $this ArchiveController
 * @var $model Act
 * @var $form CActiveForm
 */
$form = $this->beginWidget('CActiveForm', array(
    'method' => 'get',
    'action' => Yii::app()->createUrl('stat/index', ['type' => $model->companyType]),
    'id' => 'action-form',
    'errorMessageCssClass' => 'help-inline',
    'htmlOptions' => array('class'=>'stdform', 'novalidate'=>'novalidate'),
));
?>
<style>.ui-datepicker-calendar, .ui-datepicker .ui-datepicker-buttonpane button.ui-datepicker-current {display: none;} </style>
<table cellspacing="0" cellpadding="0" border="0" class="stdtable">
    <thead>
        <tr>
            <th>
                <?=$form->dropDownList($model, 'period', Act::$periodList, array('class' =>'select-period', 'style' => 'min-width:200px; margin-right: 10px;'))?>
                <?=$form->textField($model, 'month', array('class' => 'month-selector smallinput', 'style' => $model->period != 1 ? 'display:none' : ''))?>
            </th>
            <th>
                <?=$form->dropDownList(
                    $model,
                    'cardCompany',
                    CHtml::listData(Company::model()->findAll('type = :type', [':type' => Company::COMPANY_TYPE]), 'id', 'name'),
                    ['empty' => '-все компании-']
                )?>
            </th>
            <th>
                <?=$form->dropDownList(
                    $model,
                    'type_id',
                    CHtml::listData(Type::model()->findAll(), 'id', 'name'),
                    ['empty' => '-все типы-']
                )?>
            </th>
            <th>
                <?=$form->dropDownList(
                    $model,
                    'mark_id',
                    CHtml::listData(Mark::model()->findAll(), 'id', 'name'),
                    ['empty' => '-все марки-']
                )?>
            </th>
            <th>
                <?=CHtml::submitButton('Показать', array('class' => 'submit radius2 date-send', 'style' => 'opacity: 1;')); ?>
            </th>
        </tr>
        <tr class="header">
            <td colspan="5">&nbsp;</td>
        </tr>
    </thead>
</table>
<?php
$this->endWidget();
