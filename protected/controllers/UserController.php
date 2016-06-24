<?php

class UserController extends Controller
{
    public function actionList($type)
    {
        /** @var CHttpRequest $request */
        $request = Yii::app()->request;

        $model = new User('search');
        $model->unsetAttributes();
        $model->companyType = $type;

        if (isset($_GET['User'])) {
            $model->attributes = $_GET['User'];
        }

        $this->render('list', array(
            'model' => $model,
        ));
    }

    public function actionCreate()
    {
        $model = new User();
        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            $this->performAjaxValidation($model);
            $model->save();
        }

        $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : Yii::app()->createUrl("/user/" . $model->company->type));
    }

    public function actionUpdate($id)
    {
        $model = User::model()->findByPk((int)$id);

        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            $this->performAjaxValidation($model);
            if ($model->save()) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : Yii::app()->createUrl("/user/" . $model->company->type));
            }
        }

        $this->render('update', array(
            'model' => $model
        ));
    }

    public function actionLogin($id)
    {
        $user = User::model()->findByPk((int)$id);

        if (Yii::app()->user->checkAccess(User::ADMIN_ROLE)) {
            Yii::app()->request->cookies['was_admin'] = new CHttpCookie('was_admin', 1);
        }

        $model = new LoginForm();
        $model->username = $user->email;
        $model->password = '123';

        if ($model->validate() && $model->login()) {
            $this->redirect(Yii::app()->createUrl('home/index'));
        }

        $this->redirect(Yii::app()->createUrl('home/login'));
    }

    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/'));
    }

    public function loadModel($id)
    {
        $model = User::model()->findByPk((int)$id);
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