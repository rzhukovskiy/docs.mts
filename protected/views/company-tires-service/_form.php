<?php
/**
 * @var $this TiresController
 * @var $form CActiveForm
 * @var $typeList Type
 * @var $company Company
 * @var $model CompanyTiresService
 * @var $serviceList []
 */
$form = $this->beginWidget('CActiveForm', array(
        'id' => 'action-form',
        'action' => isset($model) ? [Yii::app()->createUrl("/tires/updatePrice", ['id' => $model->id])] : [Yii::app()->createUrl("/tires/addPrice")],
        'errorMessageCssClass' => 'help-inline',
        'htmlOptions' => array('class' => 'stdform'),
        'enableAjaxValidation' => true,
        'clientOptions' => array('validateOnSubmit' => true),
    ));
?>
<table class="stdtable grid">
    <tr>
        <td>
            <?=CHtml::checkBoxList('Type', isset($model) ? CHtml::listData($typeList, 'id', 'id') : [], CHtml::listData($typeList, 'id', 'name')); ?>
        </td>
        <td>
            <?php
                foreach($serviceList as $service) {
                    echo '<div class="clearfix">';
                    echo CHtml::label($service->description, '');
                    echo CHtml::textField("Service[$service->id]", isset($model) ? $model->price : '', ['style' => 'width: 50px; float: right;']);
                    echo '</div>';
                }
            ?>
        </td>
        <td>
            <?=CHtml::hiddenField('company_id', isset($model) ? $model->company_id : $company->id); ?>
            <?=CHtml::hiddenField('returnUrl', Yii::app()->request->urlReferrer)?>
            <?=CHtml::submitButton(isset($model) ? 'Сохранить' : 'Добавить', array('class' => 'submit radius2', 'style' => 'opacity: 1;')); ?>
        </td>
    <tr>
</table>
<?php $this->endWidget(); ?>
<!-- form -->
