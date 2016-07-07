<div class="contenttitle radiusbottom0">
    <h2 class="table"><span><?= $model->isNewRecord ? 'Создать компанию' : 'Редактировать компанию'; ?></span></h2>
</div>
<?php
/**
 * @var $this CompanyController
 * @var $form CActiveForm
 * @var $model Company
 */
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'action-form',
    'action' => array($model->isNewRecord ? Yii::app()->createUrl("/company/create") : Yii::app()->createUrl("/company/update", array("id" => $model->id))),
    'errorMessageCssClass' => 'help-inline',
    'htmlOptions' => array('class' => 'stdform'),
    'enableAjaxValidation' => true,
    'clientOptions' => array('validateOnSubmit' => true),
));
?>
<div class="row">
    <?= $form->label($model, 'name'); ?>
    <span class="field">
        <?= $form->textField($model, 'name', array('class' => 'span5')); ?>
        <?= $form->error($model, 'name'); ?>
    </span>
</div>
<div class="row">
    <?= $form->label($model, 'parent_id'); ?>
    <span class="field">
        <?= $form->dropDownList(
            $model,
            'parent_id',
            CHtml::listData(Company::model()->findAll('type = :type', [':type' => 'company']), 'id', 'name'),
            ['class' => 'span5', 'empty' => '']
        ); ?>
        <?= $form->error($model, 'parent_id'); ?>
    </span>
</div>
<div class="row">
    <?= $form->label($model, 'address'); ?>
    <span class="field">
        <?= $form->textField($model, 'address', array('class' => 'span5')); ?>
        <?= $form->error($model, 'address'); ?>
    </span>
</div>
<div class="row">
    <span data-toggle="collapse" data-target="#demo" class="pseudo">Подробнее</span>
</div>
<div id="demo" class="collapse">
    <div class="row">
        <?= $form->label($model, 'is_split'); ?>
        <span class="field">
            <?= $form->checkBox($model, 'is_split', array('class' => 'span5')); ?>
            <?= $form->error($model, 'is_split'); ?>
        </span>
    </div>
    <div class="row">
        <?= $form->label($model, 'cardList'); ?>
        <span class="field">
            <?= $form->textField($model, 'cardList', array('class' => 'span5')); ?>
            <?= $form->error($model, 'cardList'); ?>
        </span>
    </div>
    <div class="row">
        <span class="field">
            Для актов
        </span>
    </div>
    <div class="row">
        <?= $form->label($model, 'contact'); ?>
        <span class="field">
            <?= $form->textField($model, 'contact', array('class' => 'span5')); ?>
            <?= $form->error($model, 'contact'); ?>
        </span>
    </div>
    <?php foreach (Company::$listService as $service => $serviceName) { ?>
        <div class="row">
            <span class="field">
                <strong><?= $serviceName ?></strong>
            </span>
        </div>
        <div class="row">
            <?= $form->label($model, 'contract'); ?>
            <span class="field">
                <?= CHtml::hiddenField('Requisites[service_type][]', $service); ?>
                <?= CHtml::textField('Requisites[contract][]', $model->getRequisites($service, 'contract'), array('class' => 'span5')); ?>
            </span>
        </div>
        <div class="row">
            <?= $form->label($model, 'act_header'); ?>
            <span class="field">
                <?= CHtml::textArea('Requisites[header][]', $model->getRequisites($service, 'header'),array('class' => 'span5')); ?>
            </span>
        </div>
    <?php } ?>
</div>

<div class="row">
    <span class="field">
        <?= CHtml::submitButton($model->isNewRecord ? 'Создать компанию' : 'Сохранить', array('class' => 'submit radius2', 'style' => 'opacity: 1;')); ?>
    </span>
</div>
<?php $this->endWidget(); ?>
<!-- form -->
