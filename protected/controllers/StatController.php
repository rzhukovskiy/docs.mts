<?php
class StatController extends Controller
{
    public function actionIndex($type = false)
    {
        $model = new Act('search');
        $model->unsetAttributes();

        if (!$type) {
            $type = Company::CARWASH_TYPE;
        }
        $model->companyType = $type;

        $model->showCompany = Yii::app()->getRequest()->getParam('showCompany', false);

        if (isset($_GET['Act'])) {
            $model->attributes = $_GET['Act'];
        }

        if (!isset($model->end_date)) {
            $model->end_date = date('Y-m-d');
        }

        if (!isset($model->start_date)) {
            $model->start_date = date('Y-m-01', strtotime($model->end_date . ' 00:00:00'));
        }

        $this->render('index', array(
            'model' => $model,
        ));
    }
}
