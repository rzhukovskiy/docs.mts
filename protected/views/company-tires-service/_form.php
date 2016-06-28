<?php
/**
 * @var $this CompanyController
 * @var $form CActiveForm
 * @var $model Company
 * @var $priceList Price
 */
$form = $this->beginWidget('CActiveForm', array(
        'id' => 'action-form',
        'action' => array(Yii::app()->createUrl("/tires/addPrice")),
        'errorMessageCssClass' => 'help-inline',
        'htmlOptions' => array('class' => 'stdform'),
        'enableAjaxValidation' => true,
        'clientOptions' => array('validateOnSubmit' => true),
    ));
?>
<table class="stdtable grid">
    <tr>
        <td>
            <?=CHtml::checkBoxList('Type', [], CHtml::listData(Type::model()->findAll(), 'id', 'name')); ?>
        </td>
        <td>
            <?php
                foreach(TiresService::model()->findAll(['condition' => 'is_fixed = 1', 'order' => 'pos']) as $service) {
                    echo '<div class="clearfix">';
                    echo CHtml::label($service->description, '');
                    echo CHtml::textField("Service[$service->id]", '', ['style' => 'width: 50px; float: right;']);
                    echo '</div>';
                }
            ?>
        </td>
        <td>
            <?=CHtml::hiddenField('company_id', $model->id); ?>
            <?=CHtml::hiddenField('returnUrl', Yii::app()->request->urlReferrer)?>
            <?=CHtml::submitButton('Добавить', array('class' => 'submit radius2', 'style' => 'opacity: 1;')); ?>
        </td>
    <tr>
</table>
<?php $this->endWidget(); ?>
<!-- form -->
