<?php
class StatController extends Controller
{
    public function actionIndex($type = false)
    {
        $model = new Act('search');
        $model->unsetAttributes();

        if ($type) {
            $model->companyType = $type;
        }

        $model->showCompany = Yii::app()->getRequest()->getParam('showCompany', false);

        if (isset($_GET['Act'])) {
            $model->attributes = $_GET['Act'];
        }
        if (!isset($_GET['Act']['month'])) {
            $model->service_date = date('Y-m-d', time());
        }

        $this->render('index', ['model' => $model]);
    }

    public function actionMonths($type = false)
    {
        $model = new Act('search');
        $model->unsetAttributes();

        if ($type) {
            $model->companyType = $type;
        }

        $model->showCompany = Yii::app()->getRequest()->getParam('showCompany', false);

        if (isset($_GET['Act'])) {
            $model->attributes = $_GET['Act'];
        }
        if (!isset($_GET['Act']['month'])) {
            $model->service_date = date('Y-m-d', time());
        }

        $this->render('months', ['model' => $model]);
    }

    public function actionDays($type = false)
    {
        $model = new Act('search');
        $model->unsetAttributes();

        if ($type) {
            $model->companyType = $type;
        }

        $model->showCompany = Yii::app()->getRequest()->getParam('showCompany', false);

        if (isset($_GET['Act'])) {
            $model->attributes = $_GET['Act'];
        }
        if (!isset($_GET['Act']['month'])) {
            $model->service_date = date('Y-m-d', time());
        }

        $this->render('days', ['model' => $model]);
    }

    public function actionDetails($type = false)
    {
        $model = new Act('search');
        $model->unsetAttributes();

        if ($type) {
            $model->companyType = $type;
        }

        $model->showCompany = Yii::app()->getRequest()->getParam('showCompany', false);

        if (isset($_GET['Act'])) {
            $model->attributes = $_GET['Act'];
        }

        $this->render('details', ['model' => $model]);
    }
}
