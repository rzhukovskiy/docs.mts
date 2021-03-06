<?php
/**
 * @var $this ActController
 * @var $form CActiveForm
 * @var $model Act
 */
//$this->renderPartial('_autoselect');
$attributes = $model->attributeLabels();
$form = $this->beginWidget('CActiveForm', array(
        'id' => 'action-form',
        'action' => array($model->isNewRecord ? Yii::app()->createUrl("/act/create") : Yii::app()->createUrl("/act/update", array("id" => $model->id, 'showCompany' => $model->showCompany))),
        'errorMessageCssClass' => 'help-inline',
        'enableAjaxValidation' => true,
        'clientOptions' => array('validateOnSubmit' => true),
        'htmlOptions' => array('enctype' => 'multipart/form-data', 'class'=>'stdform', 'novalidate'=>'novalidate'),
    ));
?>
<div class="row">
    <?=$form->label($model, 'service_date'); ?>
    <span class="field">
        <?=$form->textField($model, 'service_date', array('class' => 'span5')); ?>
        <?=$form->error($model, 'service_date'); ?>
    </span>
</div>

<?php if(Yii::app()->user->checkAccess(User::ADMIN_ROLE)) { ?>
    <div class="row">
        <?=$form->label($model, 'partner_id'); ?>
        <span class="field">
            <?=$form->dropDownList($model, 'partner_id', CHtml::listData(Company::model()->findAll('type = :type', array(':type' => $model->companyType)), 'id', 'name')); ?>
            <?=$form->error($model, 'partner_id'); ?>
        </span>
    </div>
<?php } ?>

<div class="row">
    <?=$form->label($model, 'card_id'); ?>
    <span class="field">
        <?=CHtml::textField('Act[cardNumber]', ($card = Card::model()->findByPk($model->card_id)) ? $card->number : $model->card_id); ?>
        <?=$form->error($model, 'card_id'); ?>
    </span>
</div>

<div class="row">
    <?=$form->label($model, 'number'); ?>
    <span class="field">
        <?=$form->textField($model, 'number'); ?>
        <?=$form->error($model, 'number'); ?>
    </span>
</div>

<div class="row">
    <?=$form->label($model, 'extra_number'); ?>
    <span class="field">
        <?=$form->textField($model, 'extra_number'); ?>
        <?=$form->error($model, 'extra_number'); ?>
    </span>
</div>

<div class="row">
    <?=$form->label($model, 'mark_id'); ?>
    <span class="field">
        <?=$form->dropDownList($model, 'mark_id', CHtml::listData(Mark::model()->findAll(array('order' => 'id')), 'id', 'name')); ?>
        <?=$form->error($model, 'mark_id'); ?>
    </span>
</div>

<div class="row">
    <?=$form->label($model, 'type_id'); ?>
    <span class="field">
        <?=$form->dropDownList($model, 'type_id', CHtml::listData(Type::model()->findAll(array('order' => 'id')), 'id', 'name')); ?>
        <?=$form->error($model, 'type_id'); ?>
    </span>
</div>

<?php if($model->partner->type == Company::CARWASH_TYPE) { ?>
    <div class="row">
        <?=$form->label($model, 'client_service'); ?>
        <span class="field">
            <?=$form->dropDownList($model, 'client_service', Act::$carwashList); ?>
            <?=$form->error($model, 'client_service'); ?>
        </span>
    </div>

    <div class="row">
        <?=$form->label($model, 'check'); ?>
        <span class="field">
            <?=$form->textField($model, 'check'); ?>
            <?=$form->error($model, 'check'); ?>
        </span>
    </div>

    <div class="row">
        <?=$form->label($model, 'screen'); ?>
        <span class="field">
            <?=$form->fileField($model, 'screen'); ?>
            <?=$form->error($model, 'screen'); ?>
        </span>
    </div>
<?php } ?>

<?php if(
    Yii::app()->user->checkAccess(User::ADMIN_ROLE)
    && $model->partner->type != Company::SERVICE_TYPE
    && $model->partner->type != Company::TIRES_TYPE
) { ?>
    <div class="row">
        <?=$form->label($model, 'income'); ?>
        <span class="field">
            <?=$form->textField($model, 'income'); ?>
            <?=$form->error($model, 'income'); ?>
        </span>
    </div>
<?php } ?>

<?php if((Yii::app()->user->checkAccess(User::ADMIN_ROLE) || !$model->is_closed) && $model->service != Company::CARWASH_TYPE && $model->service != Company::DISINFECTION_TYPE) {
    $this->renderPartial('_scope', array('model' => $model));
} ?>

<div class="row">
    <span class="field">
        <?=CHtml::hiddenField('Act[old_expense]', $model->expense)?>
        <?=CHtml::hiddenField('Act[old_income]', $model->income)?>
        <?=CHtml::hiddenField('returnUrl', Yii::app()->request->urlReferrer)?>
        <?=CHtml::submitButton('??????????????????', array('class' => 'submit radius2', 'style' => 'opacity: 1;')); ?>
    </span>
</div>
<?php $this->endWidget(); ?>
<!-- form -->
