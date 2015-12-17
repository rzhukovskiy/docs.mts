<?php
class StatController extends Controller
{
    public function actionIndex($type = false)
    {
        $model = new Act('search');
        $model->unsetAttributes();

        if (!$type) {
            $type = Yii::app()->user->role == User::MANAGER_ROLE ? Yii::app()->user->model->company->type : Company::CARWASH_TYPE;
        }
        $model->companyType = $type;

        $model->showCompany = Yii::app()->getRequest()->getParam('showCompany', false);

        if (isset($_GET['Act'])) {
            $model->attributes = $_GET['Act'];
        }
        if (!isset($_GET['Act']['month'])) {
            $model->service_date = date('Y-m-d', time());
        }

        $this->render('index', array(
            'model' => $model,
        ));
    }
}
