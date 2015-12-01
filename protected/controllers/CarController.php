<?php

class CarController extends Controller
{
    public function actionList()
    {
        $model = new Car('search');
        $model->unsetAttributes();

        if (isset($_GET['Car'])) {
            $model->attributes = $_GET['Car'];
        }

        $this->render('list', array(
            'model' => $model,
        ));
    }

    public function actionHistory($id)
    {
        $carModel = $this->loadModel((int)$id);

        $model = new Act('search');
        if (isset($_GET['Act'])) {
            $model->attributes = $_GET['Act'];
        }
        $model->number = $carModel->number;

        $this->render('history', array(
            'model' => $model,
            'id' => $id,
        ));
    }

    public function loadModel($id)
    {
        $model = Car::model()->findByPk((int)$id);
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