<?php

class TiresController extends Controller
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
            $model->save();
        }

        $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array($this->type . '/list'));
    }

    public function actionUpdate($id)
    {
        $model = Company::model()->findByPk((int)$id);

        if (isset($_POST['Company'])) {
            $model->attributes = $_POST['Company'];
            $this->performAjaxValidation($model);
            if ($model->save()) {
                $this->redirect(Yii::app()->createUrl($this->type . '/list'));
            }
        }

        $priceList = new CompanyTiresService();
        $priceList->company_id = $id;
        $this->render('update', array(
            'model' => $model,
            'priceList' => $priceList,
            'typeList' => Type::model()->findAll(),
            'serviceList' => TiresService::model()->findAll(['condition' => 'is_fixed = 1', 'order' => 'pos']),
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
        CompanyTiresService::model()->findByPk($id)->delete();

        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/'));
    }

    public function actionAddPrice()
    {
        $company_id = Yii::app()->request->getParam('company_id', false);
        if ($company_id && isset($_POST['Type'])) {
            foreach ($_POST['Type'] as $type_id) {
                foreach ($_POST['Service'] as $service_id => $price) {
                    if ($price) {
                        $CompanyTiresService = new CompanyTiresService();
                        $CompanyTiresService->company_id = $company_id;
                        $CompanyTiresService->tires_service_id = $service_id;
                        $CompanyTiresService->type_id = $type_id;
                        $CompanyTiresService->price = $price;
                        $CompanyTiresService->save();
                    }
                }
            }
        }

        $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : Yii::app()->createUrl('/tires/list'));
    }

    public function actionUpdatePrice($id)
    {
        $companyTiresService = CompanyTiresService::model()->findByPk($id);
        if (!$companyTiresService) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        if (isset($_POST['Type'])) {
            foreach ($_POST['Type'] as $type_id) {
                foreach ($_POST['Service'] as $service_id => $price) {
                    $currentServicePrice = CompanyTiresService::model()->find([
                        'condition' => 'company_id = :company_id AND price = :price AND tires_service_id = :service_id AND type_id = :type_id',
                        'params' => [
                            ':company_id' => $companyTiresService->company_id,
                            ':price'      => $companyTiresService->price,
                            ':service_id' => $service_id,
                            ':type_id'    => $type_id,
                        ],
                    ]);
                    if ($currentServicePrice) {
                        $currentServicePrice->price = $price;
                        $currentServicePrice->save();
                    }
                }
            }

            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : Yii::app()->createUrl('/tires/list'));
        }

        $this->render('/company-tires-service/update', array(
            'model' => $companyTiresService,
            'typeList' => Type::model()->findAll([
                'with' => [
                    'companyTiresService'
                ],
                'condition' => 'companyTiresService.company_id = :company_id AND companyTiresService.price = :price AND companyTiresService.tires_service_id = :service_id',
                'params' => [
                    ':company_id' => $companyTiresService->company_id,
                    ':price'      => $companyTiresService->price,
                    ':service_id' => $companyTiresService->tires_service_id,
                ],
            ]),
            'serviceList' => TiresService::model()->findAll([
                'condition' => 'id = :id',
                'params' => [
                    ':id' => $companyTiresService->tires_service_id,
                ],
                'order' => 'pos'
            ]),
        ));
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