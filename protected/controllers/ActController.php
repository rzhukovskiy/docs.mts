<?php

class ActController extends Controller
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
        if (Yii::app()->user->checkAccess(User::ADMIN_ROLE)) {
            $clientScript = Yii::app()->getClientScript();
            $clientScript->registerScriptFile('/js/sticker.js');
        }

        $model = new Act('search');
        $model->unsetAttributes();
        $model->companyType = $type;
        $model->showCompany = Yii::app()->getRequest()->getParam('showCompany', false);

        if (isset($_GET['Act'])) {
            $model->attributes = $_GET['Act'];
            $model->day = isset($_GET['Act']['day']) && $_GET['Act']['day'] ? str_pad($_GET['Act']['day'], 2, '0', STR_PAD_LEFT) : false;
        }

        if (Yii::app()->user->checkAccess(User::ADMIN_ROLE)) {
            $model->service_date = date('Y-m-d', time() - 30 * 24 * 3600);
        } else {
            $model->create_date = date('Y-m-d', time());
        }

        if ($this->isExportRequest()) {
            $time = strtotime($model->service_date . ' 00:00:00');
            $model->updateAll(array('is_closed' => 1), 'date_format(service_date, "%Y-%m") = :date', array(':date' => date('Y-m', $time)));
            $this->exportCSV($model);

            $this->render("acts", array(
                'model' => $model,
            ));
        } else {
            $this->render('list', array(
                'model' => $model,
            ));
        }

    }

    public function actionFix()
    {
        $model = new Act('search');
        $model->unsetAttributes();
        $model->companyType = Company::CARWASH_TYPE;
        $model->showCompany = Yii::app()->getRequest()->getParam('showCompany', false);

        if (isset($_GET['Act'])) {
            $model->attributes = $_GET['Act'];
            $model->day = isset($_GET['Act']['day']) && $_GET['Act']['day'] ? str_pad($_GET['Act']['day'], 2, '0', STR_PAD_LEFT) : false;
        }

        if (Yii::app()->user->checkAccess(User::ADMIN_ROLE)) {
            $model->service_date = date('Y-m-d', time() - 30 * 24 * 3600);
        }

        $actList = $model->search()->getData();

        foreach ($actList as $act) {
            $act->old_expense = $act->expense;
            $act->old_income = $act->income;
            $act->fixMode = true;
            $act->save();
        }

        $this->redirect(isset(Yii::app()->request->urlReferrer) ? Yii::app()->request->urlReferrer : array('act/carwash'));
    }

    public function actionCreate()
    {
        $model = new Act();
        if (isset($_POST['Act'])) {
            $model->attributes = $_POST['Act'];
            if (!Yii::app()->user->checkAccess(User::ADMIN_ROLE)) {
                $model->company_id = Yii::app()->user->model->company_id;
            }
            if (!empty($_FILES)) {
                if (property_exists($model, 'screen')) {
                    $dir = $_SERVER['DOCUMENT_ROOT'] . '/files/checks/';
                    $model->screen = CUploadedFile::getInstance($model, 'screen');
                    if ($model->screen) {
                        $model->check_image = md5(uniqid(rand(), 1)) . '.jpg';
                        $model->screen->saveAs($dir . $model->check_image);
                    }
                }
            }
            $this->performAjaxValidation($model);

            if (isset($_POST['Scope'])) {
                $scopeList = $_POST['Scope'];
                $total = 0;
                for ($i = 0; $i < count($scopeList['expense']) || $i < count($scopeList['amount']); $i++) {
                    $total += abs($scopeList['expense'][$i]) * abs($scopeList['amount'][$i]);
                }
                $model->income = $model->expense = $total;
            }

            if ($model->save() && isset($_POST['Scope'])) {
                $scopeList = $_POST['Scope'];

                for ($i = 0; $i < count($scopeList['expense']) || $i < count($scopeList['description']); $i++) {
                    $scope = new ActScope();
                    $scope->act_id = $model->id;
                    $scope->description = $scopeList['description'][$i];
                    $scope->expense = $scope->income = $scopeList['expense'][$i];
                    $scope->amount = $scopeList['amount'][$i];
                    $scope->save();
                }
            }
        }

        $this->redirect(isset(Yii::app()->request->urlReferrer) ? Yii::app()->request->urlReferrer : array('act/carwash'));
    }

    public function actionUpdate($id)
    {
        $model = Act::model()->findByPk((int)$id);
        $model->showCompany = Yii::app()->getRequest()->getParam('showCompany', false);
        $model->companyType = $model->company->type;

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
                foreach ($oldScopes as $scope) {
                    $scope->delete();
                }

                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : Yii::app()->createUrl('act/carwash'));
            }
        }

        $view = $model->showCompany ? 'company' : 'service';
        $this->render("$view/update", array(
            'model' => $model
        ));
    }

    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        if (!isset($_GET['ajax']))
            $this->redirect(isset(Yii::app()->request->urlReferrer) ? Yii::app()->request->urlReferrer : array('act/carwash'));
    }

    public function actionDeleteCompany($id)
    {
        $this->loadModel($id)->delete();

        if (!isset($_GET['ajax']))
            $this->redirect(isset(Yii::app()->request->urlReferrer) ? Yii::app()->request->urlReferrer : array('act/company'));
    }

    public function loadModel($id)
    {
        $model = Act::model()->findByPk((int)$id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'action-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
