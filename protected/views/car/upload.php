<?php
/**
 * @var $this CarController
 * @var $model Car
 */

$this->renderPartial('_tabs', array('model'=>$model));

$form = $this->beginWidget('CActiveForm', array(
    'method' => 'post',
    'id' => 'action-form',
    'errorMessageCssClass' => 'help-inline',
    'htmlOptions' => array('enctype' => 'multipart/form-data', 'class'=>'stdform', 'novalidate'=>'novalidate'),
));
?>
    <div class="row">
        <?=$form->label($model, 'company_id'); ?>
        <span class="field">
            <?=$form->dropDownList($model, 'company_id', CHtml::listData(Company::model()->findAll('type = :type', [':type' => Company::COMPANY_TYPE]), 'id', 'name')); ?>
            <?=$form->error($model, 'company_id'); ?>
        </span>
    </div>

    <div class="row">
        <?=$form->label($model, 'type_id'); ?>
        <span class="field">
            <?=$form->dropDownList($model, 'type_id', CHtml::listData(Type::model()->findAll(), 'id', 'name')); ?>
            <?=$form->error($model, 'type_id'); ?>
        </span>
    </div>

    <div class="row">
        <?=$form->label($model, 'external'); ?>
        <span class="field">
                <?=$form->fileField($model, 'external'); ?>
                <?=$form->error($model, 'external'); ?>
            </span>
    </div>

    <div class="row">
        <span class="field">
            <?=CHtml::submitButton('Загрузить', array('class' => 'submit radius2', 'style' => 'opacity: 1;')); ?>
        </span>
    </div>
<?php
$this->endWidget();