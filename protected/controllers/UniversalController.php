<?php

class UniversalController extends Controller
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

            if ($model->save() && isset($_POST['service'])) {
                foreach ($_POST['service'] as $key => $value) {
                    if ($value) {
                        $modelService = new CompanyService();
                        $modelService->company_id = $model->id;
                        $modelService->service = $key;
                        $modelService->save();
                    }
                }
            }
        }

        $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array($this->type . '/list'));
    }

    public function actionUpdate($id)
    {
        $model = Company::model()->findByPk((int)$id);

        $priceList = new Price('search');
        $priceList->company_id = $model->id;
        $priceList->extra = new ExtraPrice();

        if (isset($_POST['Company'])) {
            $model->attributes = $_POST['Company'];
            $this->performAjaxValidation($model);
            if ($model->save() && isset($_POST['service'])) {
                foreach ($_POST['service'] as $key => $value) {
                    $existedService = CompanyService::model()->find('company_id = :company_id AND service = :service', [':company_id' => $model->id , ':service' => $key]);
                    if ($existedService && !$value) {
                        $existedService->delete();
                    } elseif ($existedService && $value) {
                        continue;
                    } elseif ($value) {
                        $modelService = new CompanyService();
                        $modelService->company_id = $model->id;
                        $modelService->service = $key;
                        $modelService->save();
                    }
                }
                $this->redirect(Yii::app()->createUrl($this->type . '/list'));
            }
        }

        $this->render('update', array(
            'model' => $model,
            'priceList' => $priceList
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