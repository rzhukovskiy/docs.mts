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
        if (Yii::app()->user->checkAccess(User::ADMIN_ROLE) && $companyId != $data->client_id) {
            if($companyId != 0) {
?>
                <tr class="total">
                    <td><strong>Итого</strong></td>
                    <td colspan="<?=Yii::app()->user->checkAccess(User::ADMIN_ROLE) && $model->companyType == Company::CARWASH_TYPE
                        ? 7
                        : ($model->companyType == Company::CARWASH_TYPE || Yii::app()->user->checkAccess(User::ADMIN_ROLE) ? 6 : 5); ?>">
                    </td>
                    <td style="text-align:center;"><strong><?=number_format($total, 0, ".", " ")?></strong></td>
                    <td colspan="4"></td>
                </tr>
<?php
            }
            $total = 0;
            $row = 1;
            $companyId = $data->client_id;
?>
            <tr class="header">
                <td colspan="<?=$model->companyType == Company::CARWASH_TYPE ? 13 : 10?>"><strong><?=$data->client->name . ' - ' . $data->client->address?></strong></td>
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
                <td style="width: 100px;"><?=$data->client->name?></td>
            <?php } ?>
            <td style="width: 60px;" class="<?=$cardError ? "error" : ""?>"><?=$data->card->number?></td>
            <td style="width: 80px; text-align:center;" class="<?=$numError ? "error" : ""?>"><?=$data->number?></td>
            <td style="width: 80px;" class="<?=isset($data->mark) ? "" : "error"?>"><?=isset($data->mark) ? $data->mark->name : 'неизвестно'?></td>
            <td class="<?=isset($data->type) ? "" : "error"?>"><?=isset($data->type) ? $data->type->name : 'неизвестно'?></td>
            <?php if ($model->companyType == Company::CARWASH_TYPE) { ?>
                <td style="width: 80px; text-align:center;"><?=Act::$fullList[$data->client_service]?></td>
            <?php } ?>
            <td style="width: 60px; text-align:center;" class="<?=$data->income ? "" : "error"?>"><?=$data->getFormattedField('income')?></td>
            <td style="text-align:center;"><?=$data->client->address?></td>
            <?php if ($model->companyType == Company::CARWASH_TYPE) { ?>
                <td style="width: 60px; text-align:center;" class="<?=!empty($data->check_image) ? "" : "error"?>"><?=$data->check?></td>
                <td style="width: 40px;"><a class="preview" href="/files/checks/<?=$data->check_image?>"><?=!empty($data->check_image) ? 'image' : ''?></a></td>
            <?php } ?>
            <td style="width: 75px;" class="button-column">
                    <?php if (Yii::app()->user->checkAccess(User::ADMIN_ROLE) || !$data->is_closed) { ?>
                        <a class="update" title="" href="<?=Yii::app()->createUrl('act/update', array('id' => $data->id,'showCompany' => 1))?>"></a>
                        <a class="delete" title="" href="/act/delete?id=<?=$data->id?>&returnUrl=<?='/act/carwash?Act[month]=' . $model->month?>"></a>
                    <?php } ?>
            </td>
        </tr>
<?php
        $row++;
        $cnt++;
        $total += $data->income;
        if($cnt == count($dataList)) {
            ?>
            <tr class="total">
                <td><strong>Итого</strong></td>
                <td colspan="<?=Yii::app()->user->checkAccess(User::ADMIN_ROLE) && $model->companyType == Company::CARWASH_TYPE
                    ? 7
                    : ($model->companyType == Company::CARWASH_TYPE || Yii::app()->user->checkAccess(User::ADMIN_ROLE) ? 6 : 5); ?>">
                </td>
                <td style="text-align:center;"><strong><?=number_format($total, 0, ".", " ")?></strong></td>
                <td colspan="<?=$model->companyType == Company::CARWASH_TYPE ? 4 : 2?>"></td>
            </tr>
            <?php
        }
    }
?>
