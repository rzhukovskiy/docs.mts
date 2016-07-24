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

    public function actionDisinfectAll()
    {
        $model = new Car('search');
        $model->unsetAttributes();

        $infectedCarList = false;
        if (isset($_POST['Car'])) {
            $model->attributes = $_POST['Car'];
            $infectedCarList = $model->infected();

            foreach ($infectedCarList->getData() as $car) {
                $act = new Act();
                $existedCard = Card::model()->find('company_id = :company_id', [':company_id' => $model->company_id]);
                if (!$existedCard) {
                    $error = 'У компании нет карт';
                }
                $act->card_id = $existedCard->id;

                $act->month = $model->month;
                $act->disinfectCar($car);
            }
        }

        $this->render('disinfectAll', ['model' => $model, 'infectedCarList' => $infectedCarList]);
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

        if (Yii::app()->user->checkAccess(User::ADMIN_ROLE)) {
            $model->month = date('Y-m', time() - 30 * 24 * 3600);
        } else {
            $model->create_date = date('Y-m-d', time());
        }

        if (isset($_GET['Act'])) {
            $model->attributes = $_GET['Act'];
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
        $model->companyType = Yii::app()->getRequest()->getParam('type', Company::CARWASH_TYPE);
        $model->showCompany = Yii::app()->getRequest()->getParam('showCompany', false);

        if (Yii::app()->user->checkAccess(User::ADMIN_ROLE)) {
            $model->month = date('Y-m', time() - 30 * 24 * 3600);
        } else {
            $model->create_date = date('Y-m-d', time());
        }

        if (isset($_GET['Act'])) {
            $model->attributes = $_GET['Act'];
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
                $model->partner_id = Yii::app()->user->model->company_id;
            }
            if ($card = Card::model()->find('number = :number', [':number' => $model->cardNumber])) {
                $model->client_id = $card->company_id;
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
                $totalExpense = 0;
                $totalIncome = 0;
                for ($i = 1; $i < count($scopeList['expense']) || $i < count($scopeList['amount']); $i++) {
                    if ($model->service == Company::TIRES_TYPE) {
                        $tiresService = CompanyTiresService::model()->find('company_id = :company_id AND tires_service_id = :tires_service_id',[
                            ':company_id' => $model->partner->id,
                            ':tires_service_id' => $scopeList['description'][$i],
                        ]);
                        if ($tiresService && $tiresService->price) {
                            $totalExpense += $tiresService->price * abs($scopeList['amount'][$i]);
                        } else {
                            $totalExpense += abs($scopeList['expense'][$i]) * abs($scopeList['amount'][$i]);
                        }
                        $tiresService = CompanyTiresService::model()->find('company_id = :company_id AND tires_service_id = :tires_service_id',[
                            ':company_id' => $model->client->id,
                            ':tires_service_id' => $scopeList['description'][$i],
                        ]);
                        if ($tiresService && $tiresService->price) {
                            $totalIncome += $tiresService->price * abs($scopeList['amount'][$i]);
                        } else {
                            $totalIncome += 1.2 * abs($scopeList['expense'][$i]) * abs($scopeList['amount'][$i]);
                        }
                    } else {
                        $totalExpense += abs($scopeList['expense'][$i]) * abs($scopeList['amount'][$i]);
                        $totalIncome = $totalExpense;
                    }
                }
                $model->expense = $totalExpense;
                $model->income = $totalIncome;
            }

            if ($model->save() && isset($_POST['Scope'])) {
                $scopeList = $_POST['Scope'];
                for ($i = 1; $i < count($scopeList['expense']) || $i < count($scopeList['description']); $i++) {
                    $scope = new ActScope();
                    $scope->act_id = $model->id;
                    $scope->description = $scopeList['description'][$i];
                    if ($model->service == Company::TIRES_TYPE) {
                        $scope->description = TiresService::model()->findByPk($scopeList['description'][$i])->description;
                        $tiresService = CompanyTiresService::model()->find('company_id = :company_id AND tires_service_id = :tires_service_id',[
                            ':company_id' => $model->partner->id,
                            ':tires_service_id' => $scopeList['description'][$i],
                        ]);
                        if ($tiresService && $tiresService->price) {
                            $scope->expense = $tiresService->price;
                        } else {
                            $scope->expense = abs($scopeList['expense'][$i]);
                        }
                        $tiresService = CompanyTiresService::model()->find('company_id = :company_id AND tires_service_id = :tires_service_id',[
                            ':company_id' => $model->client->id,
                            ':tires_service_id' => $scopeList['description'][$i],
                        ]);
                        if ($tiresService && $tiresService->price) {
                            $scope->income = $tiresService->price;
                        } else {
                            $scope->income = 1.2 * abs($scopeList['expense'][$i]);
                        }
                    } else {
                        $scope->expense = $scope->income = $scopeList['expense'][$i];
                    }
                    $scope->amount = $scopeList['amount'][$i];
                    $scope->save();
                }
            }

            if($model->service == Company::TIRES_TYPE && isset(Yii::app()->user->model->company) && Yii::app()->user->model->company->is_sign) {
                return $this->redirect(Yii::app()->createUrl('act/sign', ['id' => $model->id]));
            }
        }

        return $this->redirect(isset(Yii::appY()->request->urlReferrer) ? Yii::app()->request->urlReferrer : array('act/carwash'));
    }

    public function actionSign($id)
    {
        $model = Act::model()->findByPk((int)$id);

        if (isset($_POST['name'])) {
            $data = explode('base64,', $_POST['name']);

            $str = base64_decode($data[1]);
            $image = imagecreatefromstring($str);

            imagealphablending($image, false);
            imagesavealpha($image, true);
            $dir = $_SERVER['DOCUMENT_ROOT'] . '/files/signs/';
            imagepng($image, $dir . $id . '-name.png');
            $model->sign = $id;
            $model->save();
            echo CJSON::encode(['file' => $id]);
            Yii::app()->end();
        }

        if (isset($_POST['sign'])) {
            $data = explode('base64,', $_POST['sign']);

            $str = base64_decode($data[1]);
            $image = imagecreatefromstring($str);

            imagealphablending($image, false);
            imagesavealpha($image, true);
            $dir = $_SERVER['DOCUMENT_ROOT'] . '/files/signs/';
            imagepng($image, $dir . $id . '-sign.png');
            echo CJSON::encode(['file' => $id]);
            Yii::app()->end();
        }

        $this->render("sign", array(
            'model' => $model
        ));
    }

    public function actionUpdate($id)
    {
        $model = Act::model()->findByPk((int)$id);
        $model->showCompany = Yii::app()->getRequest()->getParam('showCompany', false);
        $model->companyType = $model->partner->type;

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
