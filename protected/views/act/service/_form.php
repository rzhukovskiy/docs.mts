<div class="contenttitle radiusbottom0">
    <h2 class="table"><span>Добавить услугу</span></h2>
</div>
<?php
/**
 * @var $this ActController
 * @var $form CActiveForm
 * @var $model Act
 */
$this->renderPartial('_autoselect');
$attributes = $model->attributeLabels();
$form = $this->beginWidget('CActiveForm', array(
        'id' => 'action-form',
        'action' => array($model->isNewRecord ? Yii::app()->createUrl("/act/create") : Yii::app()->createUrl("/act/update", array("id" => $model->id))),
        'errorMessageCssClass' => 'help-inline',
        'enableAjaxValidation' => true,
        'clientOptions' => array('validateOnSubmit' => true),
        'htmlOptions' => array('enctype' => 'multipart/form-data', 'class'=>'stdform', 'novalidate'=>'novalidate'),
    ));
?>

<table cellspacing="0" cellpadding="0" border="0" class="stdtable">
    <thead>
        <tr>
            <th>
                <?=$attributes['service_date']; ?>
            </th>
            <?php if(Yii::app()->user->checkAccess(User::ADMIN_ROLE)) { ?>
                <th>
                    <?=$attributes['company_id']; ?>
                </th>
            <?php } ?>
            <th style="width: 80px;">
                <?=$attributes['card_id']; ?>
            </th>
            <th>
                <?=$attributes['number']; ?>
            </th>
            <th>
                <?=$attributes['mark_id']; ?>
            </th>
            <th>
                <?=$attributes['type_id']; ?>
            </th>
            <?php if ($model->companyType == Company::CARWASH_TYPE) { ?>
                <th>
                    <?=$attributes['service']; ?>
                </th>
                <th>
                    <?=$attributes['check']; ?>
                </th>
                <th>
                    <?=$attributes['screen']; ?>
                </th>
                <th>
            </th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                <?=$form->textField($model, 'service_date', array('class' => 'date-select', 'style' => 'width:70px')); ?>
            </td>
            <?php if(Yii::app()->user->checkAccess(User::ADMIN_ROLE)) { ?>
                <td>
                    <?=$form->dropDownList($model, 'company_id', CHtml::listData(Company::model()->findAll('type = :type', array(':type' => $model->companyType)), 'id', 'name')); ?>
                </td>
            <?php } ?>
            <td>
                <?=$form->dropDownList($model, 'card_id', CHtml::listData(Card::model()->findAll(), 'id', 'num')); ?>
            </td>
            <td>
                <?=$form->textField($model, 'number', array('style' => 'width:80px')); ?>
            </td>
            <td>
                <?=$form->dropDownList($model, 'mark_id', CHtml::listData(Mark::model()->findAll(array('order' => 'id')), 'id', 'name')); ?>
            </td>
            <td>
                <?=$form->dropDownList($model, 'type_id', CHtml::listData(Type::model()->findAll(array('order' => 'id')), 'id', 'name'), array('style' => 'width:100px')); ?>
            </td>
            <?php if ($model->companyType == Company::CARWASH_TYPE) { ?>
                <td>
                    <?=$form->dropDownList($model, 'service', Act::$carwashList, array('style' => 'width:80px')); ?>
                </td>
                <td>
                    <?=$form->textField($model, 'check', array('style' => 'width:60px')); ?>
                </td>
                <td>
                    <?=$form->fileField($model, 'screen'); ?>
                </td>
                <td>
                    <?=CHtml::submitButton('+', array('class' => 'submit radius2', 'style' => 'opacity: 1;')); ?>
                </td>
            <?php } ?>
        </tr>
    </tbody>
</table>
<?php if ($model->companyType != Company::CARWASH_TYPE) { ?>
<table cellspacing="0" cellpadding="0" border="0" class="stdtable">
    <thead>
        <tr><th colspan="4">Состав работ</th></tr>
        <tr>
            <th style="width: 700px;">Вид работ</th>
            <th>Количество</th>
            <th>Цена 1 ед.</th>
            <th style="width: 80px;"></th>
        </tr>
    </thead>
    <tbody>
        <tr class="scope example">
            <td><?=CHtml::textField('Scope[description][]')?></td>
            <td><?=CHtml::numberField('Scope[amount][]', 1)?></td>
            <td><?=CHtml::textField('Scope[sum][]')?></td>
            <td>
                <?=CHtml::button('+', array('class' => 'add_scope', 'style' => 'float: left; margin-right: 10px'))?>
                <?=CHtml::button('-', array('class' => 'remove_scope', 'style' => 'float: left'))?>
            </td>
        </tr>
        <tr class="scope">
            <td><?=CHtml::textField('Scope[description][]')?></td>
            <td><?=CHtml::numberField('Scope[amount][]', 1)?></td>
            <td><?=CHtml::textField('Scope[sum][]')?></td>
            <td>
                <?=CHtml::button('+', array('class' => 'add_scope', 'style' => 'float: left; margin-right: 10px'))?>
                <?=CHtml::button('-', array('class' => 'remove_scope', 'style' => 'float: left'))?>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <?=CHtml::submitButton('Добавить', array('class' => 'submit radius2', 'style' => 'opacity: 1;')); ?>
            </td>
        </tr>
    </tbody>
</table>
<?php } ?>
<br />
<?php $this->endWidget(); ?>
<!-- form -->
