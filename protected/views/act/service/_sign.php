<?php
/**
 * @var $this ActController
 * @var $form CActiveForm
 * @var $model Act
 */
?>
<table cellspacing="0" cellpadding="0" border="0" class="act">
    <tr>
        <th>Дата</th>
        <th>№ Карты</th>
        <th>Марка ТС</th>
        <th colspan="2">Госномер</th>
        <th>Город</th>
    </tr>
    <tr class="strong">
        <td><?=date("m/d/Y", strtotime($model->service_date))?></td>
        <td><?=$model->card->number?></td>
        <td><?=$model->mark->name?></td>
        <td colspan="2"><?=$model->number?></td>
        <td><?=$model->partner->address?></td>
    </tr>

    <tr class="header">
        <td colspan="3">Вид услуг</td>
        <td>Кол-во</td>
        <td>Стоимость</td>
        <td>Сумма</td>
    </tr>

    <?php $num = 1; $total = 0; foreach ($model->scope as $scope) { ?>
        <tr>
            <td colspan="3"><?=$num . '. ' . $scope->description?></td>
            <td><?=$scope->amount?></td>
            <td><?=$scope->income?></td>
            <td><?=$scope->income * $scope->amount?></td>
        </tr>
    <?php $num++; $total += $scope->income * $scope->amount; } ?>

    <tr class="strong">
        <td colspan="3">Итого</td>
        <td><?=--$num?></td>
        <td></td>
        <td><?=$total?></td>
    </tr>
</table>

<div class="row" style="margin-top: 50px;">
    <span class="sign">
            По качеству работы претензий не имею.
    </span>
</div>

<div class="row" style="margin-top: 50px;">
    <table class="sign">
        <tr>
            <td>
                ФИО водителя
            </td>

            <td colspan="2">
                <img style="width:200px" src="<?='/files/signs/' . $model->sign . '-name.png'?>"/>
            </td>
            <td>
                Подпись водителя
            </td>

            <td colspan="2">
                <img style="width:200px" src="<?='/files/signs/' . $model->sign . '-sign.png'?>"/>
            </td>
        </tr>
    </table>
</div>