<?php

class CardController extends Controller
{
    public function actionList()
    {
        $model = new Card('search');
        $model->unsetAttributes();

        if (isset($_GET['Card'])) {
            $model->attributes = $_GET['Card'];
        }
        $model->active = 1;

        $this->render('list', array(
            'model' => $model,
        ));
    }

    public function actionCreate()
    {
        $model = new Card();
        if (isset($_POST['Card'])) {
            $model->attributes = $_POST['Card'];
            $this->performAjaxValidation($model);
            $model->save();
        }

        $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('card/list'));
    }

    public function actionUpdate($id)
    {
        $model = Card::model()->findByPk((int)$id);

        if (isset($_POST['Card'])) {
            $model->attributes = $_POST['Card'];
            $this->performAjaxValidation($model);
            if ($model->save()) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : Yii::app()->createUrl('card/list'));
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
        $model = Card::model()->findByPk((int)$id);
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