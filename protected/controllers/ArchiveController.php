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

    public function actionSign($id)
    {
        $model = Act::model()->findByPk((int)$id);
        $model->showCompany = Yii::app()->getRequest()->getParam('showCompany', false);

        $this->render("sign", array(
            'model' => $model
        ));
    }

    public function actionList($type)
    {
        $model = new Act('search');
        $model->unsetAttributes();
        $model->companyType = $type != 'list' ? $type : Company::CARWASH_TYPE;

        $model->month = date('Y-m', time() - 30 * 24 * 3600);

        if (isset($_GET['Act'])) {
            $model->attributes = $_GET['Act'];
        }
        $company = new Company();
        $company->month = $model->month;
        $company->parent_id = Yii::app()->user->model->company_id;

        $this->render('list', array(
            'model' => $model,
            'company' => $company,
        ));
    }

    public function actionError($type = null)
    {
        $model = new Act('search');
        $model->companyType = $type;
        $provider = $model->withErrors()->search();

//        Если нужно пользоваться фильтром для поиска по таблице раскоментировать
//        $model->unsetAttributes();
//        if (isset($_GET['Act'])) {
//            $model->attributes = $_GET['Act'];
//        }

        // Генерируем табы навигации
        $currentTitle = 'Все ошибочные';
        foreach ( Company::$listService as $service => $name ) {
            $this->tabs[ $model->companyType != $service ? $service : 'list' ] = array(
                'url' => Yii::app()->createUrl( "archive/error?type=$service" ),
                'name' => $name,
                'active' => ( $type == $service ),
                'sufix' => $this->getCountActsByType($service),
            );
            $currentTitle = ($type == $service) ? $name : $currentTitle ;
        }

        $this->render('error', array(
            'model' => $model,
            'provider' => $provider,
            'title' => $currentTitle,
        ));
    }

    public function actionFix($id)
    {
        $model = Act::model()->findByPk((int)$id);
        $model->is_fixed = 1;
        $model->save();
        $this->redirect(Yii::app()->createUrl('archive/error'));
    }

    public function actionUpdate($id)
    {
        $model = Act::model()->findByPk((int)$id);
        $model->showCompany = Yii::app()->getRequest()->getParam('showCompany', false);

        if (isset($_POST['Act'])) {
            $model->attributes = $_POST['Act'];
            if (!empty($_FILES)) {
                if (property_exists($model, 'screen')) {
                    $dir = $_SERVER['DOCUMENT_ROOT'] . '/files/checks/';
                    $model->screen = CUploadedFile::getInstance($model, 'screen');
                    if ($model->screen) {
                        $model->check_image = md5(uniqid(rand(), 1)) . '.jpg';
                        $model->screen->saveAs($dir . $model->check_image);
                        Yii::app()->ih
                            ->load($dir . $model->check_image)
                            ->resize(400, 400, true)
                            ->save($dir . $model->check_image);
                    }
                }
            }
            $this->performAjaxValidation($model);

            $sumName = $model->showCompany ? 'income' : 'expense';
            if (isset($_POST['Scope'])) {
                $scopeList = $_POST['Scope'];
                $total = 0;
                for ($i = 0; $i < count($scopeList[$sumName]) || $i < count($scopeList['amount']); $i++) {
                    $total += abs($scopeList[$sumName][$i]) * abs($scopeList['amount'][$i]);
                }
                $model->$sumName = $total;
            }

            if ($model->save()) {
                $oldScopes = CHtml::listData($model->scope, 'id', 'id');
                if (isset($_POST['Scope'])) {
                    $scopeList = $_POST['Scope'];
                    for ($i = 0; $i < count($scopeList[$sumName]) || $i < count($scopeList['description']); $i++) {
                        if ($scopeList['id'][$i]) {
                            $scope = ActScope::model()->findByPk($scopeList['id'][$i]);
                            $scope->$sumName = $scopeList[$sumName][$i];
                            unset($oldScopes[$scopeList['id'][$i]]);
                        } else {
                            $scope = new ActScope();
                            $scope->act_id = $model->id;
                            $scope->income = $scope->expense = $scopeList[$sumName][$i];
                        }
                        $scope->description = $scopeList['description'][$i];
                        $scope->amount = $scopeList['amount'][$i];
                        $scope->save();
                    }
                }
                foreach ($oldScopes as $scopeId) {
                    $scope = ActScope::model()->findByPk($scopeId);
                    if ($scope) {
                        $scope->delete();
                    }
                }

                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : Yii::app()->createUrl('archive/error'));
            }
        }

        $this->render("update", array(
            'model' => $model
        ));
    }

    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'action-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    private function getCountActsByType ($type)
    {
        $acts = Act::model()
            ->find()
            ->byType($type)
            ->withErrors()
            ->findAll();

        return count($acts);
    }
}