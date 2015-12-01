<?php

class ImageController extends Controller
{
    public function actionList()
    {
        $model = new Type('search');
        $model->unsetAttributes();

        if (isset($_GET['Type'])) {
            $model->attributes = $_GET['Type'];
        }

        $this->render('list', array(
            'model' => $model,
        ));
    }
}