<?php
/**
 * @var $this ActController
 * @var $form CActiveForm
 * @var $model Act
 */
//$this->renderPartial('_autoselect', ['model'=>$model]);
$form = $this->beginWidget('CActiveForm', array(
        'id' => 'action-form',
        'action' => Yii::app()->createUrl("/archive/update", array("id" => $model->id)),
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

    <div class="row">
        <?=$form->label($model, 'partner_id'); ?>
        <span class="field">
            <?=$form->dropDownList($model, 'partner_id', CHtml::listData(Company::model()->findAll('type = :type', array(':type' => $model->partner->type)), 'id', 'name')); ?>
            <?=$form->error($model, 'partner_id'); ?>
        </span>
    </div>

    <div class="row">
        <?=$form->label($model, 'client_id'); ?>
        <span class="field">
                <?=$form->dropDownList($model, 'client_id', CHtml::listData(Company::model()->findAll('type = :type', array(':type' => Company::COMPANY_TYPE)), 'id', 'name')); ?>
                <?=$form->error($model, 'client_id'); ?>
            </span>
    </div>

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
            <?=$form->label($model, 'partner_service'); ?>
            <span class="field">
                    <?=$form->dropDownList($model, 'partner_service', Act::$carwashList); ?>
                    <?=$form->error($model, 'partner_service'); ?>
                </span>
        </div>

        <div class="row">
            <?=$form->label($model, 'partner_service'); ?>
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

    <div class="row">
        <?=$form->label($model, 'expense'); ?>
        <span class="field">
            <?=$form->textField($model, 'expense'); ?>
            <?=$form->error($model, 'expense'); ?>
        </span>
    </div>

    <div class="row">
        <?=$form->label($model, 'income'); ?>
        <span class="field">
            <?=$form->textField($model, 'income'); ?>
            <?=$form->error($model, 'income'); ?>
        </span>
    </div>

    <?php if($model->partner->type != Company::CARWASH_TYPE) {
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
