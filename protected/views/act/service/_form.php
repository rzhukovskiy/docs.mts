<div class="contenttitle radiusbottom0">
    <h2 class="table"><span>Добавить машину</span></h2>
</div>
<?php
/**
 * @var $this ActController
 * @var $form CActiveForm
 * @var $model Act
 */
//$this->renderPartial('_autoselect');
$this->renderPartial('_autocomplete');
$attributes = $model->attributeLabels();
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'action-form',
    'action' => array($model->isNewRecord ? Yii::app()->createUrl("/act/create") : Yii::app()->createUrl("/act/update", array("id" => $model->id))),
    'errorMessageCssClass' => 'help-inline',
    'enableAjaxValidation' => true,
    'clientOptions' => array('validateOnSubmit' => true),
    'htmlOptions' => array('enctype' => 'multipart/form-data', 'class' => 'stdform', 'novalidate' => 'novalidate'),
));

if ($model->companyType == Company::TIRES_TYPE) {
    ?>
    <script>
        $(document).ready(function () {
            var serviceList = <?=json_encode( CHtml::listData(TiresService::model()->findAll(), 'id', 'is_fixed'))?>;
            $('.tires-expense').hide();
            $(document).on('change', '.tires-select', function () {
                var fixed = serviceList[$(this).val()];
                if (fixed > 0) {
                    $(this).parent().parent().find('.tires-expense').hide();
                } else {
                    $(this).parent().parent().find('.tires-expense').show();
                }
            });
        });
    </script>
    <?php
}
?>

<table cellspacing="0" cellpadding="0" border="0" class="stdtable">
    <thead>
    <tr>
        <th>
            <?= $attributes['service_date']; ?>
        </th>
        <?php if (Yii::app()->user->checkAccess(User::ADMIN_ROLE)) { ?>
            <th>
                <?= $attributes['partner_id']; ?>
            </th>
        <?php } ?>
        <th style="width: 80px;">
            <?= $attributes['card_id']; ?>
        </th>
        <th>
            <?= $attributes['number']; ?>
        </th>
        <th class="extra-number">
            <?= $attributes['extra_number']; ?>
        </th>
        <th>
            <?= $attributes['mark_id']; ?>
        </th>
        <th>
            <?= $attributes['type_id']; ?>
        </th>
        <?php if ($model->companyType == Company::CARWASH_TYPE) { ?>
            <th>
                <?= $attributes['partner_service']; ?>
            </th>
            <th>
                <?= $attributes['check']; ?>
            </th>
            <th>
                <?= $attributes['screen']; ?>
            </th>
        <?php } ?>

        <?php if ($model->companyType == Company::DISINFECTION_TYPE) { ?>
            <th>
                <?= $attributes['partner_service']; ?>
            </th>
        <?php } ?>

        <?php if ($model->companyType == Company::CARWASH_TYPE || $model->companyType == Company::DISINFECTION_TYPE) { ?>
            <th>
            </th>
        <?php } ?>
    </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                <?= CHtml::hiddenField('Act[service]', $model->companyType); ?>
                <?= $form->textField($model, 'service_date', array('class' => 'date-select')); ?>
            </td>
            <?php if (Yii::app()->user->checkAccess(User::ADMIN_ROLE)) { ?>
                <td  style="text-align: center">
                    <?= $form->dropDownList($model, 'partner_id', CHtml::listData(Company::model()->findAll('type = :type', array(':type' => $model->companyType)), 'id', 'name')); ?>
                </td>
            <?php } ?>
            <td>
                <?= $form->textField($model, 'cardNumber'); ?>
            </td>
            <td>
                <?= $form->textField($model, 'number', array('class' => 'number_fill main-number')); ?>
            </td>
            <td class="extra-number">
                <?= $form->textField($model, 'extra_number', array('class' => 'number_fill')); ?>
            </td>
            <td>
                <?= $form->dropDownList($model, 'mark_id', CHtml::listData(Mark::model()->findAll(array('order' => 'id')), 'id', 'name')); ?>
            </td>
            <td>
                <?= $form->dropDownList($model, 'type_id',
                    CHtml::listData(Type::model()->findAll(array('order' => 'id')), 'id', 'name'), array('style' => $model->companyType == Company::CARWASH_TYPE ? 'width:100px' : 'width:200px')
                ); ?>
            </td>
            <?php if ($model->companyType == Company::CARWASH_TYPE) { ?>
                <td>
                    <?= $form->dropDownList($model, 'partner_service', Act::$carwashList, array('style' => 'width:80px')); ?>
                </td>
                <td>
                    <?= $form->textField($model, 'check', array('style' => 'width:60px')); ?>
                </td>
                <td>
                    <?= $form->fileField($model, 'screen'); ?>
                </td>
            <?php } ?>

            <?php if ($model->companyType == Company::DISINFECTION_TYPE) { ?>
                <td>
                    <?= $form->dropDownList($model, 'partner_service', Act::$disinfectionList, array('style' => 'width:80px')); ?>
                </td>
            <?php } ?>

            <?php if ($model->companyType == Company::CARWASH_TYPE || $model->companyType == Company::DISINFECTION_TYPE) { ?>
                <td>
                    <?= CHtml::submitButton('+', array('class' => 'submit radius2', 'style' => 'opacity: 1;')); ?>
                </td>
            <?php } ?>
        </tr>
    </tbody>
</table>
<?php if ($model->companyType == Company::SERVICE_TYPE || $model->companyType == Company::TIRES_TYPE) { ?>
    <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
        <thead>
        <tr>
            <th colspan="4">Состав работ</th>
        </tr>
        <tr>
            <th style="text-align: left;">Вид работ</th>
            <th style="width: 100px;">Количество</th>
            <th style="width: 100px;">Цена 1 ед.</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <tr class="scope example">
            <?php if ($model->companyType == Company::TIRES_TYPE) { ?>
                <td><?= CHtml::dropDownList('Scope[description][]', 0, CHtml::listData(TiresService::model()->findAll(['order' => 'pos']), 'id', 'description'), ['class' => 'tires-select']) ?></td>
            <?php } else { ?>
                <td><?= CHtml::textField('Scope[description][]') ?></td>
            <?php } ?>
            <td><?= CHtml::numberField('Scope[amount][]', 1, ['class' => 'tires-amount']) ?></td>
            <td><?= CHtml::textField('Scope[expense][]', '', ['class' => 'tires-expense']) ?></td>
            <td  style="text-align: center">
                <?= CHtml::button('+', array('class' => 'add_scope', 'style' => 'float: left; margin-right: 10px')) ?>
                <?= CHtml::button('-', array('class' => 'remove_scope', 'style' => 'float: left')) ?>
            </td>
        </tr>
        <tr class="scope">
            <?php if ($model->companyType == Company::TIRES_TYPE) { ?>
                <td><?= CHtml::dropDownList('Scope[description][]', 0, CHtml::listData(TiresService::model()->findAll(['order' => 'pos']), 'id', 'description'), ['class' => 'tires-select']) ?></td>
            <?php } else { ?>
                <td><?= CHtml::textField('Scope[description][]') ?></td>
            <?php } ?>
            <td><?= CHtml::numberField('Scope[amount][]', 1, ['class' => 'tires-amount']) ?></td>
            <td><?= CHtml::textField('Scope[expense][]', '', ['class' => 'tires-expense']) ?></td>
            <td  style="text-align: center">
                <?= CHtml::button('+', array('class' => 'add_scope', 'style' => 'float: left; margin-right: 10px')) ?>
                <?= CHtml::button('-', array('class' => 'remove_scope', 'style' => 'float: left')) ?>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <?= CHtml::submitButton('Загрузить', array('class' => 'submit radius2', 'style' => 'opacity: 1;')); ?>
            </td>
        </tr>
        </tbody>
    </table>
<?php } ?>
<br/>
<?php $this->endWidget(); ?>
<!-- form -->
