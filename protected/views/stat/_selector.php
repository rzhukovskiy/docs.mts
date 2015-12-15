
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
<table cellspacing="0" cellpadding="0" border="0" class="stdtable">
    <thead>
        <tr>
            <th>
                <?=$form->textField($model, 'start_date', ['class' => 'datepicker'])?>
                <?=$form->textField($model, 'end_date', ['class' => 'datepicker'])?>
            </th>
            <th>
                <?=$form->dropDownList(
                    $model,
                    'company_id',
                    CHtml::listData(Company::model()->findAll('type = :type', [':type' => $model->companyType]), 'id', 'name'),
                    ['empty' => '-все-']
                )?>
            </th>
            <th>
                <?=CHtml::submitButton('Показать', array('class' => 'submit radius2 date-send', 'style' => 'opacity: 1;')); ?>
            </th>
        </tr>
        <tr class="header">
            <td colspan="3">&nbsp;</td>
        </tr>
    </thead>
</table>
<?php
$this->endWidget();
