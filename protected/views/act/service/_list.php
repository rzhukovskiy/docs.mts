<?php
/**
 * @var $this ActController
 * @var $model Act
 */
?>
<div class="my-grid" id="act-grid">
    <table class="stdtable grid table-fixed">
        <thead>
        <?php
        $totalCount = count($model->search()->getData());
        if (Yii::app()->user->checkAccess(User::ADMIN_ROLE)) {
            $this->renderPartial('_selector', array('model' => $model));
        }
        ?>
        <tr class="selector">
            <th id="act-grid_c0">№</th>
            <th id="act-grid_c1"><a class="sort-link" href="/act/<?=$model->companyType?>?Act_sort=service_date">Дата</a></th>
            <?php if (Yii::app()->user->checkAccess(User::ADMIN_ROLE)) { ?>
                <th id="act-grid_c2"><a class="sort-link" href="/act/<?=$model->companyType?>?Act_sort=company_id">Сервис</a></th>
            <?php } ?>
            <th id="act-grid_c3"><a class="sort-link" href="/act/<?=$model->companyType?>?Act_sort=card_id">Карта</a></th>
            <th id="act-grid_c4"><a class="sort-link" href="/act/<?=$model->companyType?>?Act_sort=number">Госномер</a></th>
            <th id="act-grid_c5"><a class="sort-link" href="/act/<?=$model->companyType?>?Act_sort=mark_id">Марка</a></th>
            <th id="act-grid_c6"><a class="sort-link" href="/act/<?=$model->companyType?>?Act_sort=type_id">Тип ТС</a></th>
            <?php if ($model->companyType == Company::CARWASH_TYPE) { ?>
                <th id="act-grid_c7"><a class="sort-link" href="/act/<?=$model->companyType?>?Act_sort=service">Услуга</a></th>
            <?php } ?>
            <th id="act-grid_c8"><a class="sort-link" href="/act/<?=$model->companyType?>?Act_sort=expense">Сумма</a></th>
            <?php if ($model->companyType == Company::CARWASH_TYPE) { ?>
                <th id="act-grid_c10"><a class="sort-link" href="/act/<?=$model->companyType?>?Act_sort=check">Номер чека</a></th>
                <th id="act-grid_c11"><a class="sort-link">Чек</a></th>
            <?php } ?>
            <th class="button-column" id="act-grid_c12">&nbsp;</th>
        </tr>
        <?php if (Yii::app()->user->checkAccess(User::ADMIN_ROLE)) { ?>
            <tr class="filters">
                <td>&nbsp;</td>
                <td>
                    <?=CHtml::dropDownList('Act[day]',
                        $model->day,
                        range(0, date('t', strtotime("$model->month-$model->day"))),
                        array('empty' => 'Все'))?>
                </td>
                <td><?=CHtml::dropDownList('Act[company_id]',
                        $model->company_id,
                        CHtml::listData(Company::model()->findAll('type = :type' , array(':type' => $model->companyType)),'id', 'name'),
                        array('empty' => 'Все', 'style' => 'width: 80px;'))?>
                </td>
                <td><input name="Act[card_id]" type="text"></td>
                <td><input name="Act[number]" type="text"></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <?php if ($model->companyType == Company::CARWASH_TYPE)  { ?>
                    <td>&nbsp;</td>
                    <td><input name="Act[check]" type="text"></td>
                    <td>&nbsp;</td>
                <?php } ?>
                <td>&nbsp;</td>
            </tr>
        <?php } ?>
        </thead>
        <tbody>
            <?=$this->renderPartial('service/_item', array('model' => $model))?>
            <?php if (Yii::app()->user->checkAccess(User::ADMIN_ROLE)) { ?>
                <tr class="total">
                    <td><strong>Общее</strong></td>
                    <td colspan="<?=Yii::app()->user->checkAccess(User::ADMIN_ROLE) && $model->companyType == Company::CARWASH_TYPE ? 7 : (Yii::app()->user->checkAccess(User::ADMIN_ROLE) ? 6 : 5); ?>">
                        <?=$totalCount . ' ' . StringNum::getNumEnding($totalCount, array('машина', 'машины', 'машин'))?>
                    </td>
                    <td style="text-align:center;"><strong><?=$model->totalExpense()?></strong></td>
                    <td colspan="<?=$model->companyType == Company::CARWASH_TYPE ? 3 : 1?>"></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>