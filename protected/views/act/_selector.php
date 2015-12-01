<?php
/**
 * @var $this ActController
 * @var $model Act
 * @var $form CActiveForm
 */
?>
<tr>
    <td colspan="2">Выбор периода</td>
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'method' => 'get',
        'action' => Yii::app()->createUrl("/act/$model->companyType", array('showCompany' => $model->showCompany)),
        'id' => 'action-form',
        'errorMessageCssClass' => 'help-inline',
        'htmlOptions' => array('class'=>'stdform', 'novalidate'=>'novalidate'),
    ));
    ?>
        <td colspan="2"><?=$form->textField($model, 'month')?></td>
        <td colspan="2"><?=CHtml::submitButton('Показать', array('class' => 'submit radius2 date-send', 'style' => 'opacity: 1;')); ?></td>
    <?php
    $this->endWidget();

    $form = $this->beginWidget('CActiveForm', array(
        'method' => 'get',
        'action' => Yii::app()->createUrl("/act/fix"),
        'id' => 'action-form',
        'errorMessageCssClass' => 'help-inline',
        'htmlOptions' => array('class'=>'stdform', 'novalidate'=>'novalidate'),
    ));
    ?>
        <td>
            <?php if (Yii::app()->user->checkAccess(User::ADMIN_ROLE)) { ?>
                Пересчет цен
            <?php } ?>
        </td>
        <td>
            <?php if (Yii::app()->user->checkAccess(User::ADMIN_ROLE)) { ?>
                <?=CHtml::submitButton('Исправить', array('class' => 'submit radius2', 'style' => 'opacity: 1;')); ?>
            <?php } ?>
        </td>
    <?php
        $this->endWidget();
    ?>
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
