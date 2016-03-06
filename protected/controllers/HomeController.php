<?php

class HomeController extends Controller
{
    public function actionLogin()
    {
        $this->layout = '//layouts/login';
        $model = new LoginForm;

        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];

            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login()) {
                if (Yii::app()->user->role == User::PARTNER_ROLE) {
                    $this->redirect(Yii::app()->createUrl('act/' . Yii::app()->user->model->company->type));
                }
                if (Yii::app()->user->role == User::CLIENT_ROLE) {
                    $this->redirect(Yii::app()->createUrl('archive/' . Company::CARWASH_TYPE));
                }
                $this->redirect(Yii::app()->createUrl('company/list'));
            }
        }
        $this->render('login', array('model' => $model));
    }

    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    public function actionIndex()
    {
        if (Yii::app()->user->role == User::PARTNER_ROLE) {
            $this->redirect(Yii::app()->createUrl('act/' . Yii::app()->user->model->company->type));
        }
        if (Yii::app()->user->role == User::CLIENT_ROLE) {
            $this->redirect(Yii::app()->createUrl('archive/list'));
        }
        $this->redirect(Yii::app()->createUrl('company/list'));
    }

    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest) {
                echo $error['message'];
            }
            else
                $this->render('error', $error);
        }
    }

    protected function performAjaxValidation($model, $id_form)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === $id_form) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionFirst()
    {
        $user = new User();
        $user->name  = 'Admin';
        $user->email = 'admin@mtransservice.ru';
        $user->password = '1a2d3m4i5n';

        $user->save();

        print_r($user->getErrors());
        die;
    }
}