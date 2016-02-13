
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
            </th>
            <th style="width: 300px">
                <?php if(count(Yii::app()->user->model->company->children)) { ?>
                    <?=CHtml::label('Выбор филиала', '')?>
                    <?=$form->dropDownList($model,
                        'client_id',
                        CHtml::listData(Yii::app()->user->model->company->children, 'id', 'name'),
                        ['class' => 'autoinput', 'empty' => 'все']
                    )?>
                <?php } ?>
            </th>
            <th><?=CHtml::submitButton('Показать', array('class' => 'submit radius2', 'style' => 'opacity: 1;')); ?></th>
        </tr>
        <tr class="header">
            <td colspan="3">&nbsp;</td>
        </tr>
    </thead>
</table>
<?php
$this->endWidget();
