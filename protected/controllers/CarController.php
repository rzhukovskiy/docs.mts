<?php

class CarController extends Controller
{
    public function actionUpload()
    {
        $model = new Car();

        if (isset($_POST[get_class($model)])) {
            $model->attributes = $_POST[get_class($model)];

            if (!empty($_FILES)) {
                $model->external = CUploadedFile::getInstance($model, 'external');
                $listCar = $model->saveFromExternal();
            }

            $this->render('upload/list', array(
                'firstId' => isset($listCar[0]) ? $listCar[0]->id : 0,
            ));
        } else {
            $this->render('upload', array(
                'model' => $model
            ));
        }
    }

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

    public function actionDirty()
    {
        $model = new Car('search');
        $model->unsetAttributes();

        if (isset($_GET['Car'])) {
            $model->attributes = $_GET['Car'];
        }

        $this->render('dirty', array(
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

    public function actionDetails($id)
    {
        $model = Act::model()->findByPk($id);

        $this->render('details', array(
            'model' => $model,
            'id' => $id,
        ));
    }

    public function actionCreate()
    {
        $model = new Car();

        if (isset($_POST['Car'])) {
            $model->attributes = $_POST['Car'];
            $this->performAjaxValidation($model);
            $model->save();
        }

        $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('car/list'));
    }

    public function actionUpdate($id)
    {
        $model = Car::model()->findByPk((int)$id);

        if (isset($_POST['Car'])) {
            $model->attributes = $_POST['Car'];
            $this->performAjaxValidation($model);
            if ($model->save()) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : Yii::app()->createUrl('car/list'));
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