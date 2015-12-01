<?php

class MarkController extends Controller
{
    public function actionList()
    {
        $model = new Mark('search');
        $model->unsetAttributes();

        if (isset($_GET['Mark'])) {
            $model->attributes = $_GET['Mark'];
        }

        $this->render('list', array(
            'model' => $model,
        ));
    }

    public function actionCreate()
    {
        $model = new Mark();
        if (isset($_POST['Mark'])) {
            $model->attributes = $_POST['Mark'];
            $this->performAjaxValidation($model);
            $model->save();
        }

        $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('mark/list'));
    }

    public function actionUpdate($id)
    {
        $model = Mark::model()->findByPk((int)$id);

        if (isset($_POST['Mark'])) {
            $model->attributes = $_POST['Mark'];
            $this->performAjaxValidation($model);
            if ($model->save()) {
                $this->redirect(Yii::app()->createUrl('mark/list'));
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
        $model = Mark::model()->findByPk((int)$id);
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