<?php

class PriceController extends Controller
{
    public function actionList()
    {
        $model = new Price('search');
        $model->unsetAttributes();

        if (isset($_GET['Price'])) {
            $model->attributes = $_GET['Price'];
        }

        $this->render('list', array(
            'model' => $model,
        ));
    }

    public function actionCreate()
    {
        $model = new Price();
        if (isset($_POST['Price'])) {
            $model->attributes = $_POST['Price'];
            $this->performAjaxValidation($model);
            $model->save();
        }

        $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('Price/list'));
    }

    public function actionUpdate($id)
    {
        $model = Price::model()->findByPk((int)$id);

        if (isset($_POST['Price'])) {
            $model->attributes = $_POST['Price'];
            $this->performAjaxValidation($model);
            if ($model->save()) {
                if (isset($_POST['ExtraPrice'])) {
                    $extraPrice = ExtraPrice::model()->findByPk($model->extra->id);
                    $extraPrice->attributes = $_POST['ExtraPrice'];
                    $extraPrice->save();
                }

                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : Yii::app()->createUrl('Price/list'));
            }
        }

        $this->render('update', array(
            'model' => $model
        ));
    }

    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/'));
    }

    public function loadModel($id)
    {
        $model = Price::model()->findByPk((int)$id);
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