<?php
/**
 * @var $this ActController
 * @var $model Act
 * @var $form CActiveForm
 */
?>
<table cellspacing="0" cellpadding="0" border="0" class="stdtable selector">
    <thead>
        <tr>
            <th>
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
                    <?=CHtml::textField('Act[month]', $model->monthAsString, array('style' => ' margin-left: 20px;'))?>
                    <?=CHtml::submitButton('Показать', array('class' => 'submit radius2 date-send', 'style' => 'display: none; opacity: 1;')); ?>
                <?php
                $this->endWidget();
                ?>
            </th>
            <?php if (Yii::app()->user->checkAccess(User::ADMIN_ROLE) && ($model->companyType != Company::SERVICE_TYPE)) { ?>
                <th>
                    <?php
                    $form = $this->beginWidget('CActiveForm', array(
                        'method' => 'POST',
                        'action' => Yii::app()->createUrl("/act/fix?" . Yii::app()->getRequest()->queryString),
                        'id' => 'action-form',
                        'errorMessageCssClass' => 'help-inline',
                        'htmlOptions' => array('class'=>'stdform', 'novalidate'=>'novalidate'),
                    ));
                    ?>
                        Пересчет цен
                        <?=CHtml::hiddenField('type', $model->companyType)?>
                        <?=CHtml::submitButton('Исправить', array('class' => 'submit radius2', 'style' => 'opacity: 1; margin-left: 20px;')); ?>
                    <?php
                    $this->endWidget();
                    ?>
                </th>
            <?php } ?>
            <?php
            if (Yii::app()->user->checkAccess(User::ADMIN_ROLE) &&
                (($model->companyType != Company::SERVICE_TYPE && $model->companyType != Company::DISINFECTION_TYPE) || $model->showCompany)
            ) { ?>
            <th>
                <?php
                    $this->widget('ext.jQueryHighlight.DJqueryHighlight', array(
                        'selector' => '#act-grid',
                    ));
                    $this->renderExportGridButton('act-grid', 'Выгрузить', array('class' => 'btn-info pull-right'));
                }
                ?>
            </th>
        </tr>
        <tr class="header">
            <td colspan="3">&nbsp;</td>
        </tr>
    </thead>
</table>