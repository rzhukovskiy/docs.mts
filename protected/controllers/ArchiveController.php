<?php

class ArchiveController extends Controller
{
    public function behaviors() {
        return array(
            'exportableGrid' => array(
                'class' => 'application.components.ExportableGridBehavior',
                'filename' => 'act.xls',
            ));
    }

    public function actionList($type)
    {
        $model = new Act('search');
        $model->unsetAttributes();
        $model->companyType = $type;

        $model->service_date = date('Y-m-d', time() - 30 * 24 * 3600);

        if (Yii::app()->user->role == User::WATCHER_ROLE) {
            $model->is_closed = 1;
        }

        if (isset($_GET['Act'])) {
            $model->attributes = $_GET['Act'];
            $model->month = $_GET['Act']['month'];
        }

        $this->render('list', array(
            'model' => $model,
        ));
    }
}