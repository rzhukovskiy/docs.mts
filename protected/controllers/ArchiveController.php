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

        $model->month = date('Y-m', time() - 30 * 24 * 3600);

        if (isset($_GET['Act'])) {
            $model->attributes = $_GET['Act'];
        }

        $this->render('list', array(
            'model' => $model,
        ));
    }

    public function actionError()
    {
        $model = new Act('search');
        $model->unsetAttributes();

        if (isset($_GET['Act'])) {
            $model->attributes = $_GET['Act'];
        }

        $this->render('error', array(
            'model' => $model,
        ));
    }
}