<?php

    /**
     * Created by PhpStorm.
     * User: ruslanzh
     * Date: 05/08/16
     * Time: 10:14
     */

    class CarCountController extends Controller
    {
        public function actionList()
        {
            $companyId = Yii::app()->user->model->company_id;
            $carByTypes = Car::getCountCarsByTypes($companyId);

            $this->render('list', array('carByTypes' => $carByTypes));
        }
    }