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

        $month = isset($_GET['Act']['month']) ? $_GET['Act']['month'] : date('Y-m', time() - 30 * 24 * 3600);
        $model->month = $month;

        if (isset($_GET['Act'])) {
            $model->attributes = $_GET['Act'];
        }

        $model->from_date = $model->month . '-01';
        $month = explode('-', $model->month);
        if ($month[1] == 12) {
            $model->to_date = ($month[0] + 1) . '-01-01';
        } else {
            $model->to_date = $month[0] . '-' . ($month[1] + 1) . '-01';
        }

        $this->render('list', array(
            'model' => $model,
        ));
    }
}