<?php
/**
 * @var $this CarController
 * @var $model ActScope
 */
$row = 0;
?>
<tr class="stdtable grid act-details">
    <td colspan="8">
        <table class="stdtable grid">
            <thead>
                <tr>
                    <td style="width: 30px">№</td>
                    <td>Вид работ</td>
                    <td style="width: 60px">Количество</td>
                    <td style="width: 60px">Сумма</td>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($model->search()->getData() as $data) { ?>
                <tr>
                    <td><?=++$row?></td>
                    <td><?=$data->description?></td>
                    <td><?=$data->amount?></td>
                    <td><?=$data->sum?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </td>
</tr>