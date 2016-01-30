<?php
    /**
     * @var $this ActController
     * @var $model Act
     */
    $row = 1;
    $cnt = 0;
    $provider = $model->search();
    $dataList = $provider->getData();
    if (count($dataList) < 1) {
        return;
    }

    $total = 0;
    $companyId = 0;
    foreach ($dataList as $data) {
        if (Yii::app()->user->checkAccess(User::ADMIN_ROLE) && $companyId != $data->partner_id) {
            if($companyId != 0) {
?>
                <tr class="total">
                    <td><strong>Итого</strong></td>
                    <td colspan="<?=Yii::app()->user->checkAccess(User::ADMIN_ROLE) && $model->companyType == Company::CARWASH_TYPE
                        ? 7
                        : ($model->companyType == Company::CARWASH_TYPE || Yii::app()->user->checkAccess(User::ADMIN_ROLE) ? 6 : 5); ?>">
                    </td>
                    <td style="text-align:center;"><strong><?=number_format($total, 0, ".", " ");?></strong></td>
                    <td colspan="<?=$model->companyType == Company::CARWASH_TYPE ? 3 : 1?>"></td>
                </tr>
<?php
            }
            $total = 0;
            $row = 1;
            $companyId = $data->partner_id;
?>
            <tr class="header">
                <td colspan="<?=$model->companyType == Company::CARWASH_TYPE ? 12 : 9?>"><strong><?=$data->partner->name . ' - ' . $data->partner->address?></strong></td>
            </tr>
<?php
        }
        $carByNumber = Car::model()->find("number = :number" ,array(":number" => $data->number));
        $numError = !$carByNumber;
        $cardError = !$numError && $carByNumber->company_id != $data->card->company_id;
?>
        <tr class="<?=$row%2 ? 'even' : 'odd'?>">
            <td style="width: 40px; text-align:center;"><?=$row?></td>
            <td style="width: 70px; text-align:center;"><?=date('d', strtotime($data->service_date))?></td>
            <?php if (Yii::app()->user->checkAccess(User::ADMIN_ROLE)) { ?>
                <td style="width: 100px;"><?=$data->partner->name?></td>
            <?php } ?>
            <td style="width: 60px;" class="<?=$data->hasError("card") ? "error" : ""?>"><?=$data->card->number?></td>
            <td style="width: 80px; text-align:center;" class="<?=$data->hasError("car") ? "error" : ""?>">
                <?=$data->number ? $data->number : "error"?>
            </td>
            <td style="width: 80px;" class="<?=isset($data->mark) ? "" : "error"?>"><?=isset($data->mark) ? $data->mark->name : 'неизвестно'?></td>
            <td class="<?=isset($data->type) ? "" : "error"?>"><?=isset($data->type) ? $data->type->name : 'неизвестно'?></td>
            <?php if ($model->companyType == Company::CARWASH_TYPE) { ?>
                <td style="width: 80px; text-align:center;"><?=Act::$fullList[$data->partner_service]?></td>
            <?php } ?>
            <td style="width: 60px; text-align:center;" class="<?=$data->hasError("expense") ? "error" : ""?>"><?=$data->getFormattedField('expense') ?></td>
            <?php if ($model->companyType == Company::CARWASH_TYPE) { ?>
                <td style="width: 60px; text-align:center;" class="<?=$data->hasError("check") ? "error" : ""?>">
                    <?=$data->check ? $data->check : ($data->hasError("check") ? "error" : "")?>
                </td>
                <td style="width: 40px;"><a class="preview" href="/files/checks/<?=$data->check_image?>"><?=!empty($data->check_image) ? 'image' : ''?></a></td>
            <?php } ?>
            <td style="width: 95px;" class="button-column">
                    <?php if (Yii::app()->user->checkAccess(User::ADMIN_ROLE) || !$data->is_closed) { ?>
                        <a class="update" title="" href="<?=Yii::app()->createUrl('/act/update', array('id' => $data->id))?>"></a>
                        <a class="delete"
                           title=""
                           href="<?=Yii::app()->createUrl('/act/delete', array('id' => $data->id))?>&returnUrl=<?=Yii::app()->createUrl("/act/$model->companyType", array('Act[month]' => $model->month))?>">
                        </a>
                    <?php } ?>
            </td>
        </tr>
<?php
        $row++;
        $cnt++;
        $total += $data->expense;
        if($cnt == count($dataList)) {
?>
            <tr class="total">
                <td><strong>Итого</strong></td>
                <td colspan="<?=Yii::app()->user->checkAccess(User::ADMIN_ROLE) && $model->companyType == Company::CARWASH_TYPE
                    ? 7
                    : ($model->companyType == Company::CARWASH_TYPE || Yii::app()->user->checkAccess(User::ADMIN_ROLE) ? 6 : 5); ?>">
                </td>
                <td style="text-align:center;"><strong><?=number_format($total, 0, ".", " ")?></strong></td>
                <td colspan="<?=$model->companyType == Company::CARWASH_TYPE ? 3 : 1?>"></td>
            </tr>
<?php
        }
    }
?>
