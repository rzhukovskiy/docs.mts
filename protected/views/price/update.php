<?php
/**
 * @var $this PriceController
 * @var $model Price
 */

$this->tabs = array(
    'company' => array('url' => Yii::app()->createUrl('company/update', array('id' => $model->company_id)), 'name' => 'Редактирование компании ' . $model->company->name),
    'update' => array('url' => '#', 'name' => 'Редактирование цены ' . $model->type->name),
);

$this->renderPartial('_form', array('model'=>$model));
