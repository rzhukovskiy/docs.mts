<div class="contenttitle radiusbottom0">
    <h2 class="table"><span>Дезинфекция всех машин</span></h2>
</div>

<?php
/**
 * @var $this ActController
 * @var $model Car
 * @var $form CActiveForm
 */

$form = $this->beginWidget('CActiveForm', [
    'method' => 'post',
    'action' => Yii::app()->createUrl("/act/disinfectAll"),
    'id' => 'action-form',
    'errorMessageCssClass' => 'help-inline',
    'htmlOptions' => array('class'=>'stdform', 'novalidate'=>'novalidate'),
]);
?>
<table cellspacing="0" cellpadding="0" border="0" class="stdtable selector">
    <thead>
        <tr>
            <th>
                Выбор периода
                <?=$form->textField($model, 'month', array('style' => ' margin-left: 20px;'))?>
            </th>
            <th>
                Выбор компании
                <?=$form->dropDownList(
                    $model,
                    'company_id',
                    CHtml::listData(Company::model()->findAll('type = :type', [':type' => Company::COMPANY_TYPE]), 'id', 'name'),
                    ['style' => ' margin-left: 20px;']
                )?>
            </th>
            <th>
                <?=CHtml::submitButton('Дезинфицировать', array('class' => 'submit radius2', 'style' => 'opacity: 1; margin-left: 20px;')); ?>
            </th>
        </tr>
    </thead>
</table>
<?php
$this->endWidget();
?>