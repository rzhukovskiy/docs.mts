<?php

class ImageController extends Controller
{
    public function actionList()
    {
        $model = new Type('search');
        $model->unsetAttributes();

        if (isset($_GET['Type'])) {
            $model->attributes = $_GET['Type'];
        }

        $this->render('list', array(
            'model' => $model,
        ));
    }

    public function actionUpdate($id)
    {
        $model = Type::model()->findByPk((int)$id);

        if (isset($_POST['Type'])) {
            $model->attributes = $_POST['Type'];
            if (!empty($_FILES)) {
                if (property_exists($model, 'screen')) {
                    $dir = $_SERVER['DOCUMENT_ROOT'] . '/images/cars/';
                    $model->screen = CUploadedFile::getInstance($model, 'screen');
                    if ($model->screen) {
                        $model->image = md5(uniqid(rand(), 1)) . '.jpg';
                        $model->screen->saveAs($dir . $model->image);
                        Yii::app()->ih
                            ->load($dir . $model->image)
                            ->resize(400, 400, true)
                            ->save($dir . $model->image);
                    }
                }
            }

            $this->performAjaxValidation($model);
            if ($model->save()) {
                $this->redirect(Yii::app()->createUrl('image/list'));
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
        $model = Type::model()->findByPk((int)$id);
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