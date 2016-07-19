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
        <th>
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

        <?php if ($model->companyType == Company::CARWASH_TYPE || $model->companyType == Company::DISINFECTION_TYPE) { ?>
            <th>
            </th>
        <?php } ?>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td  style="text-align: center">
            <?= CHtml::hiddenField('Act[service]', $model->companyType); ?>
            <?= $form->textField($model, 'service_date', array('class' => 'date-select', 'style' => 'width:70px')); ?>
        </td>
        <?php if (Yii::app()->user->checkAccess(User::ADMIN_ROLE)) { ?>
            <td  style="text-align: center">
                <?= $form->dropDownList($model, 'partner_id', CHtml::listData(Company::model()->findAll('type = :type', array(':type' => $model->companyType)), 'id', 'name')); ?>
            </td>
        <?php } ?>
        <td  style="text-align: center">
            <?= $form->textField($model, 'cardNumber'); ?>
        </td>
        <td  style="text-align: center">
            <?= $form->textField($model, 'number', array('class' => 'number_fill', 'style' => 'width:80px')); ?>
        </td>
        <td  style="text-align: center">
            <?= $form->textField($model, 'extra_number', array('class' => 'number_fill', 'style' => 'width:80px')); ?>
        </td>
        <td  style="text-align: center">
            <?= $form->dropDownList($model, 'mark_id', CHtml::listData(Mark::model()->findAll(array('order' => 'id')), 'id', 'name')); ?>
        </td>
        <td  style="text-align: center">
            <?= $form->dropDownList($model, 'type_id', CHtml::listData(Type::model()->findAll(array('order' => 'id')), 'id', 'name'), array('style' => 'width:100px')); ?>
        </td>
        <?php if ($model->companyType == Company::CARWASH_TYPE) { ?>
            <td  style="text-align: center">
                <?= $form->dropDownList($model, 'partner_service', Act::$carwashList, array('style' => 'width:80px')); ?>
            </td>
            <td  style="text-align: center">
                <?= $form->textField($model, 'check', array('style' => 'width:60px')); ?>
            </td>
            <td  style="text-align: center">
                <?= $form->fileField($model, 'screen'); ?>
            </td>
        <?php } ?>

        <?php if ($model->companyType == Company::CARWASH_TYPE || $model->companyType == Company::DISINFECTION_TYPE) { ?>
            <td  style="text-align: center">
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
            <th style="width: 700px;">Вид работ</th>
            <th>Количество</th>
            <th>Цена 1 ед.</th>
            <th style="width: 80px;"></th>
        </tr>
        </thead>
        <tbody>
        <tr class="scope example">
            <?php if ($model->companyType == Company::TIRES_TYPE) { ?>
                <td  style="text-align: center"><?= CHtml::dropDownList('Scope[description][]', 0, CHtml::listData(TiresService::model()->findAll(['order' => 'pos']), 'id', 'description'), ['class' => 'tires-select']) ?></td>
            <?php } else { ?>
                <td  style="text-align: center"><?= CHtml::textField('Scope[description][]') ?></td>
            <?php } ?>
            <td  style="text-align: center"><?= CHtml::numberField('Scope[amount][]', 1, ['class' => 'tires-amount']) ?></td>
            <td  style="text-align: center"><?= CHtml::textField('Scope[expense][]', '', ['class' => 'tires-expense']) ?></td>
            <td  style="text-align: center">
                <?= CHtml::button('+', array('class' => 'add_scope', 'style' => 'float: left; margin-right: 10px')) ?>
                <?= CHtml::button('-', array('class' => 'remove_scope', 'style' => 'float: left')) ?>
            </td>
        </tr>
        <tr class="scope">
            <?php if ($model->companyType == Company::TIRES_TYPE) { ?>
                <td  style="text-align: center"><?= CHtml::dropDownList('Scope[description][]', 0, CHtml::listData(TiresService::model()->findAll(['order' => 'pos']), 'id', 'description'), ['class' => 'tires-select']) ?></td>
            <?php } else { ?>
                <td  style="text-align: center"><?= CHtml::textField('Scope[description][]') ?></td>
            <?php } ?>
            <td  style="text-align: center"><?= CHtml::numberField('Scope[amount][]', 1, ['class' => 'tires-amount']) ?></td>
            <td  style="text-align: center"><?= CHtml::textField('Scope[expense][]', '', ['class' => 'tires-expense']) ?></td>
            <td  style="text-align: center">
                <?= CHtml::button('+', array('class' => 'add_scope', 'style' => 'float: left; margin-right: 10px')) ?>
                <?= CHtml::button('-', array('class' => 'remove_scope', 'style' => 'float: left')) ?>
            </td>
        </tr>
        <?php if (isset(Yii::app()->user->model->company) && Yii::app()->user->model->company->is_sign) { ?>
            <tr>
                <td colspan="3">
                    Фамилия и инициалы водителя:
                    <div id="wPaint1" style="position:relative; width:600px; height:100px; background-color:#eee; border: 1px solid #eee;">
                    </div>
                    <script type="text/javascript">
                        function saveSign() {
                            var image = $('#wPaint1').wPaint('image');

                            $.ajax({
                                type: 'POST',
                                url: '/act/create',
                                data: {name: image},
                                success: function (resp) {
                                    resp = $.parseJSON(resp);
                                    image = $('#wPaint2').wPaint('image');
                                    $.ajax({
                                        type: 'POST',
                                        url: '/act/create?file=' + resp.file,
                                        data: {sign: image},
                                        success: function (resp) {
                                            resp = $.parseJSON(resp);
                                            var data = $('form').serialize() + '&Act[sign]=' + resp.file;
                                            $.ajax({
                                                type: 'POST',
                                                url: '/act/create',
                                                data: data,
                                                success: function (resp) {
                                                    document.location.href = document.location.href;
                                                }
                                            });
                                        }
                                    });
                                }
                            });
                        }

                        // init wPaint
                        $('#wPaint1').wPaint({
                            path: '/js/wpaint/',
                            saveImg:     saveSign,
                            bg:          '#fff',
                            lineWidth:   '1',       // starting line width
                            fillStyle:   '#fff', // starting fill style
                            strokeStyle: '#3355aa'  // start stroke style
                        });
                    </script>
                </td>
                <td>
                    <?=CHtml::button('Очистить', array('class' => 'submit radius2', 'onclick' => "$('#wPaint1').wPaint('clear');")); ?>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    Подпись водителя:
                    <div id="wPaint2" style="position:relative; width:600px; height:100px; background-color:#eee; border: 1px solid #eee;">
                    </div>
                    <script type="text/javascript">
                        // init wPaint
                        $('#wPaint2').wPaint({
                            path: '/js/wpaint/',
                            saveImg:     saveSign,
                            bg:          '#fff',
                            lineWidth:   '1',       // starting line width
                            fillStyle:   '#fff', // starting fill style
                            strokeStyle: '#3355aa'  // start stroke style
                        });
                    </script>
                </td>
                <td>
                    <?=CHtml::button('Очистить', array('class' => 'submit radius2', 'onclick' => "$('#wPaint2').wPaint('clear');")); ?>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <?= CHtml::button('Загрузить', array('class' => 'submit radius2', 'style' => 'opacity: 1;', 'onclick' => "saveSign();")); ?>
                </td>
            </tr>
        <?php } else { ?>
            <tr>
                <td colspan="4">
                    <?= CHtml::submitButton('Загрузить', array('class' => 'submit radius2', 'style' => 'opacity: 1;')); ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
<?php } ?>
<br/>
<?php $this->endWidget(); ?>
<!-- form -->
