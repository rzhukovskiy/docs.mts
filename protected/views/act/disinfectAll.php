<?php
/**
 * @var $this ActController
 * @var $model Car
 * @var $infectedCarList CActiveDataProvider
 */
if (
    Yii::app()->user->checkAccess(User::ADMIN_ROLE)
    || Yii::app()->user->model->company->type == Company::UNIVERSAL_TYPE
) {
    foreach (Company::$listService as $service => $name) {
        if (Yii::app()->user->checkAccess(User::ADMIN_ROLE) ||
            Yii::app()->user->model->company->carwash == $service ||
            Yii::app()->user->model->company->remont == $service ||
            Yii::app()->user->model->company->tires == $service ||
            Yii::app()->user->model->company->disinfection == $service
        ) {

            $this->tabs[$service] = [
                'url' => Yii::app()->createUrl("act/$service"),
                'name' => $name
            ];
        }
    }
    if (Yii::app()->user->model->company->is_main) {
        $this->tabs['disinfectAll'] = [
            'url' => Yii::app()->createUrl("act/disinfectAll"),
            'name' => 'Дезинфекция по компаниям'
        ];
    }
}

echo '<style>.ui-datepicker-calendar, .ui-datepicker .ui-datepicker-buttonpane button.ui-datepicker-current {display: none;}</style>';
$this->renderPartial('disinfect/_form', array('model' => $model));
if ($infectedCarList) {
    $this->renderPartial("disinfect/_list", array('infectedCarList' => $infectedCarList));
}
