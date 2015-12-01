<?php

class ActScopeController extends Controller
{
    public function actionAjaxList($actId)
    {
        $this->layout = false;
        $model = new ActScope('search');
        $model->unsetAttributes();

        if (isset($_GET['ActScope'])) {
            $model->attributes = $_GET['ActScope'];
        }
        $model->act_id = $actId;

        $this->render('list', array(
            'model' => $model,
        ));
    }
}