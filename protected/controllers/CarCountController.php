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

            $carByTypes = Car::model()
                ->getCountCarsByTypes($companyId);

            $countCarsByType = Car::model()
                ->totalField($carByTypes, 'cars_count');

            $this->render('list', array(
                'carByTypes' => $carByTypes,
                'countCarsByType' => $countCarsByType,
                'companyId' => $companyId,
                ));
        }

        public function actionCarsDetailedStatistic( $type )
        {
            $criteria = Car::model();
            $companyId = Yii::app()->user->model->company_id;
            $companyModel = null;

            if (!is_null($companyId)) {
                $companyModel = Company::model()
                    ->findByPk( $companyId );
                $criteria = $criteria
                    ->byCompany($companyId);
            }

            $typeModel = Type::model()
                ->findByPk($type);

            $criteria = $criteria
                ->byType($type)
                ->getDbCriteria();

            $provider = new CActiveDataProvider('Car', array(
                'criteria' => $criteria,
                'pagination' => false,
            ));

            $this->render('cars_detailed_statistic', array(
                'provider' => $provider,
                'companyModel' => $companyModel,
                'typeModel' => $typeModel,
            ));
        }
    }