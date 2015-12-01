<?php
/**
 * @var $this ActController
 * @var $model Act
 * @var $form CActiveForm
 */
?>
<tr>
    <th colspan="6">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'method' => 'get',
            'action' => Yii::app()->createUrl("/act/$model->companyType", array('showCompany' => $model->showCompany)),
            'id' => 'action-form',
            'errorMessageCssClass' => 'help-inline',
            'htmlOptions' => array('class'=>'stdform', 'novalidate'=>'novalidate'),
        ));
        ?>
            <?=CHtml::label('Выбор периода', '')?>
            <?=$form->textField($model, 'month')?>
            <?=CHtml::submitButton('Показать', array('class' => 'submit radius2 date-send', 'style' => 'display: none; opacity: 1;')); ?>
        <?php
        $this->endWidget();
        ?>
    </th>
    <th colspan="2">
        <?php if (Yii::app()->user->checkAccess(User::ADMIN_ROLE)) { ?>
            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'method' => 'get',
                'action' => Yii::app()->createUrl("/act/fix"),
                'id' => 'action-form',
                'errorMessageCssClass' => 'help-inline',
                'htmlOptions' => array('class'=>'stdform', 'novalidate'=>'novalidate'),
            ));
            ?>
                <?=CHtml::label('Пересчет цен', '')?>
                <?=CHtml::submitButton('Исправить', array('class' => 'submit radius2', 'style' => 'opacity: 1;')); ?>
            <?php
            $this->endWidget();
            ?>
        <?php } ?>
    </th>
    <td colspan="<?=$model->companyType == Company::CARWASH_TYPE ? 4 : 1?>">
        <?php
        if (Yii::app()->user->checkAccess(User::ADMIN_ROLE)) {
            $this->widget('ext.jQueryHighlight.DJqueryHighlight', array(
                'selector' => '#act-grid',
            ));
            $this->renderExportGridButton('act-grid', 'Выгрузить', array('class' => 'btn-info pull-right'));
        }
        ?>
    </td>
</tr>
<tr class="header">
    <td colspan="<?=$model->companyType == Company::CARWASH_TYPE ? 12 : 9?>">&nbsp;</td>
</tr>