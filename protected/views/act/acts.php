<?php
/**
 * @var $this ActController
 * @var $model Act
 */

$this->tabs = array(
    'list' => array('url' => '#', 'name' => 'Скачивание актов'),
);

$view = $model->showCompany ? 'company' : 'service';
$this->renderPartial("$view/_act_list", array('model' => $model));
