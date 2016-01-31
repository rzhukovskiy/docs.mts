
<?php
/**
 * @var $this ArchiveController
 * @var $model Company
 */
?>
<table cellspacing="0" cellpadding="0" border="0" class="stdtable">
    <?php foreach ($model->withEmptyActs()->search()->getData() as $company) { ?>
    <tbody>
        <tr class="header">
            <td>
                <?=$company->name?> - <?=$company->address?>
            </td>
        </tr>
        <tr>
            <td>Нет актов</td>
        </tr>
    </tbody>
    <?php } ?>
</table>
