
<?php
/**
 * @var $this CarController
 * @var $model Act
 * @var $form CActiveForm
 */
$form = $this->beginWidget('CActiveForm', array(
    'method' => 'get',
    'action' => Yii::app()->createUrl("/car/history", array('id' => $id)),
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
                <?=$form->dropDownList($model, 'period', Act::$periodList)?>
                <?=CHtml::submitButton('Показать', array('class' => 'submit radius2', 'style' => 'opacity: 1;')); ?>
            </th>
            <th></th>
        </tr>
        <tr class="header">
            <td colspan="2">&nbsp;</td>
        </tr>
    </thead>
</table>
<?php
$this->endWidget();
