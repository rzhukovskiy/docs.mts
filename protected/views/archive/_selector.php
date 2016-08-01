
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
                <?=CHtml::textField('Act[month]', $model->monthAsString)?>
            </th>
            <th style="width: 400px">
                <?php if(count(Yii::app()->user->model->company->children)) { ?>
                    <?=CHtml::label('Выбор филиала', '')?>
                    <?=$form->dropDownList($model,
                        'client_id',
                        CHtml::listData(Company::model()->findAll('parent_id = :parent_id AND is_deleted = 0', ['parent_id' => Yii::app()->user->model->company->id]), 'id', 'name'),
                        ['class' => 'autoinput', 'empty' => 'все']
                    )?>
                <?php } ?>
                <?=CHtml::submitButton('Показать',['class' => 'submit radius2 date-send', 'style' => 'opacity: ' . (count(Yii::app()->user->model->company->children) ? '1' : '0')]); ?></th>
        </tr>
        <tr class="header">
            <td colspan="3">&nbsp;</td>
        </tr>
    </thead>
</table>
<?php
$this->endWidget();
