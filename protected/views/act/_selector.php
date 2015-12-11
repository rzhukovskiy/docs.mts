<?php
/**
 * @var $this ActController
 * @var $model Act
 * @var $form CActiveForm
 */
?>
<tr>
    <th colspan="5">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'method' => 'get',
            'action' => Yii::app()->createUrl("/act/$model->companyType", array('showCompany' => $model->showCompany)),
            'id' => 'action-form',
            'errorMessageCssClass' => 'help-inline',
            'htmlOptions' => array('class'=>'stdform', 'novalidate'=>'novalidate'),
        ));
        ?>
            Выбор периода
            <?=$form->textField($model, 'month', array('style' => ' margin-left: 20px;'))?>
            <?=CHtml::submitButton('Показать', array('class' => 'submit radius2 date-send', 'style' => 'display: none; opacity: 1;')); ?>
        <?php
        $this->endWidget();
        ?>
    </th>
    <th colspan="3">
        <?php if (Yii::app()->user->checkAccess(User::ADMIN_ROLE) && $model->companyType == Company::CARWASH_TYPE) { ?>
            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'method' => 'get',
                'action' => Yii::app()->createUrl("/act/fix?" . Yii::app()->getRequest()->queryString),
                'id' => 'action-form',
                'errorMessageCssClass' => 'help-inline',
                'htmlOptions' => array('class'=>'stdform', 'novalidate'=>'novalidate'),
            ));
            ?>
                Пересчет цен
                <?=CHtml::submitButton('Исправить', array('class' => 'submit radius2', 'style' => 'opacity: 1; margin-left: 20px;')); ?>
            <?php
            $this->endWidget();
            ?>
        <?php } ?>
    </th>
    <th colspan="<?=$model->companyType == Company::CARWASH_TYPE ? ($model->showCompany ? 5 : 4) : ($model->showCompany ? 2 : 1)?>">
        <?php
        if (Yii::app()->user->checkAccess(User::ADMIN_ROLE)) {
            $this->widget('ext.jQueryHighlight.DJqueryHighlight', array(
                'selector' => '#act-grid',
            ));
            $this->renderExportGridButton('act-grid', 'Выгрузить', array('class' => 'btn-info pull-right'));
        }
        ?>
    </th>
</tr>
<tr class="header">
    <td colspan="<?=$model->companyType == Company::CARWASH_TYPE ? ($model->showCompany ? 13 : 12) : ($model->showCompany ? 10 : 9)?>">&nbsp;</td>
</tr>