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

        if (isset($_GET['Act'])) {
            $model->attributes = $_GET['Act'];
        }

        $this->render('index', ['model' => $model]);
    }

    public function actionTotal()
    {
        $model = new Act('search');
        $model->unsetAttributes();

        if (isset($_GET['Act'])) {
            $model->attributes = $_GET['Act'];
        }

        $this->render('total', ['model' => $model]);
    }

    public function actionMonths($type = false)
    {
        $model = new Act('search');
        $model->unsetAttributes();

        if ($type) {
            $model->companyType = $type;
        }

        if (isset($_GET['Act'])) {
            $model->attributes = $_GET['Act'];
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

        if (isset($_GET['Act'])) {
            $model->attributes = $_GET['Act'];
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

        if (isset($_GET['Act'])) {
            $model->attributes = $_GET['Act'];
        }

        $this->render('details', ['model' => $model]);
    }
}
