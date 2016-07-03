<?php

class CompanyController extends Controller
{
    private $type;
    
    public function init()
    {
        $this->type = Yii::app()->controller->id;
    }
    
    public function actionList()
    {
        $model = new Company('search');
        $model->unsetAttributes();
        $model->type = $this->type;

        if (isset($_GET['Company'])) {
            $model->attributes = $_GET['Company'];
        }

        $this->render('list', array(
            'model' => $model,
        ));
    }

    public function actionCreate()
    {
        $model = new Company();
        if (isset($_POST['Company'])) {
            $model->attributes = $_POST['Company'];
            $model->type = $this->type;
            $this->performAjaxValidation($model);

            if ($model->save() && isset($_POST['Requisites'])) {
                $listRequisites = $_POST['Requisites'];
                for($i = 0; $i < count($listRequisites); $i++) {
                    $requisites = new Requisites();
                    $requisites->company_id = $model->id;
                    $requisites->service_type = $listRequisites['service_type'][$i];
                    $requisites->contract = $listRequisites['contract'][$i];
                    $requisites->header = $listRequisites['header'][$i];
                    $requisites->save();
                }
            }
        }

        $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array($this->type . '/list'));
    }

    public function actionUpdate($id)
    {
        $model = Company::model()->findByPk((int)$id);

        $carModel = new Car();
        $carModel->company_id = $model->id;

        $priceList = new Price('search');
        $priceList->company_id = $model->id;
        $priceList->extra = new ExtraPrice();

        $tiresServiceList = new CompanyTiresService();
        $tiresServiceList->company_id = $id;

        if (isset($_POST['Company'])) {
            $model->attributes = $_POST['Company'];
            $this->performAjaxValidation($model);

            if ($model->save() && isset($_POST['Requisites'])) {
                $listRequisites = $_POST['Requisites'];
                Requisites::model()->deleteAll('company_id = :company_id', [':company_id' => $model->id]);

                for($i = 0; $i < count($listRequisites['service_type']); $i++) {
                    $requisites = new Requisites();
                    $requisites->company_id = $model->id;
                    $requisites->service_type = $listRequisites['service_type'][$i];
                    $requisites->contract = $listRequisites['contract'][$i];
                    $requisites->header = $listRequisites['header'][$i];
                    $requisites->save();
                }
            }

            $this->redirect(Yii::app()->createUrl($this->type . '/list'));
        }

        if (isset($_GET['Car'])) {
            $carModel->attributes = $_GET['Car'];
        }

        $this->render('update', array(
            'model'            => $model,
            'carModel'         => $carModel,
            'priceList'        => $priceList,
            'tiresServiceList' => $tiresServiceList,
            'typeList' => Type::model()->findAll(),
            'serviceList' => TiresService::model()->findAll(['condition' => 'is_fixed = 1', 'order' => 'pos']),
        ));
    }

    public function actionCards($id)
    {
        $model = Company::model()->findByPk((int)$id);

        $cardModel = new Card();
        $cardModel->company_id = $model->id;

        if (isset($_GET['Card'])) {
            $cardModel->attributes = $_GET['Card'];
        }

        $this->render('cards', array(
            'model' => $model,
            'cardModel' => $cardModel,
        ));
    }

    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/'));
    }

    public function actionDeletePrice($id)
    {
        Price::model()->findByPk($id)->delete();

        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/'));
    }

    public function actionAddPrice()
    {
        $model = new Price();
        if (isset($_POST['Price'])) {
            $model->attributes = $_POST['Price'];
            $this->performAjaxValidation($model);
            $model->save();
        }
        if (isset($_POST['ExtraPrice'])) {
            $extraPrice = new ExtraPrice();
            $extraPrice->attributes = $_POST['ExtraPrice'];
            $extraPrice->price_id = $model->id;
            $extraPrice->save();
        }

        $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : Yii::app()->createUrl($this->type . '/update', array('id' => $model->company_id)));
    }

    public function loadModel($id)
    {
        $model = Company::model()->findByPk((int)$id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'action-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}