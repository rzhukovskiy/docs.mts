<?php
    /**
     * Created by PhpStorm.
     * User: ruslanzh
     * Date: 05/08/16
     * Time: 10:32
     *
     * @var $this CarCountController;
     * @var $carByTypes array;
     */

    $this->tabs = array(
        'list' => array('url' => false, 'name' => 'По типу ТС'),
    );

    $this->renderPartial('_types', array(
        'carByTypes' => $carByTypes,
        'countCarsByType' => $countCarsByType,
        'companyId' => $companyId,
        ));