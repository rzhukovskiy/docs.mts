<div class="contenttitle radiusbottom0">
    <h2 class="table"><span>Добавить пользователя</span></h2>
</div>
<?php
/**
 * @var $this UserController
 * @var $form CActiveForm
 * @var $model User
 */
$form = $this->beginWidget('CActiveForm', array(
        'id' => 'action-form',
        'action' => array($model->isNewRecord ? Yii::app()->createUrl("/user/create") : Yii::app()->createUrl("/user/update", array("id" => $model->id))),
        'errorMessageCssClass' => 'help-inline',
        'htmlOptions' => array('class' => 'stdform'),
        'enableAjaxValidation' => true,
        'clientOptions' => array('validateOnSubmit' => true),
    ));
?>
<div class="row">
    <?=$form->labelEx($model, 'email'); ?>
    <span class="field">
        <?=$form->textField($model, 'email', array('class' => 'span5')); ?>
        <?=$form->error($model, 'email'); ?>
    </span>
</div>
<div class="row">
    <?=$form->labelEx($model, 'password'); ?>
    <span class="field">
        <?=CHtml::textField('User[password]', '', array('class' => 'span5')); ?>
        <?=$form->error($model, 'password'); ?>
    </span>
</div>
<div class="row">
    <?=$form->labelEx($model, 'company_id'); ?>
    <span class="field">
        <?=$form->dropDownList($model, 'company_id', CHtml::listData(Company::model()->findAll('type = :type', array(':type' => isset($model->company->type) ? $model->company->type : $model->companyType)), 'id', 'name')); ?>
        <?=$form->error($model, 'company_id'); ?>
    </span>
</div>
<div class="row stdformbutton">
    <?=$form->hiddenField($model, 'companyType'); ?>
    <?=CHtml::hiddenField('returnUrl', Yii::app()->request->urlReferrer)?>
    <?=CHtml::submitButton($model->isNewRecord ? 'Создать пользователя' : 'Сохранить', array('class' => 'submit radius2', 'style' => 'opacity: 1;')); ?>
</div>
<?php $this->endWidget(); ?>
<!-- form -->
