<?php

class WMenu extends CWidget
{
    public $msgcount;
    public $creditcount;
    public $ticketcount;
    public $paymentcount;
    public $domaincount;

    /**
     * @var $items {
     *      @var string $title required
     *      @var string $class
     *      @var string $action
     *      @var string $role
     *      @var string $visible
     *      @var array $params
     *      @var string $sufix
     * }
     */
    private $items;

    public function init()
    {
    }

    //инициализация пунктов меню
    public function getItems()
    {
        if(empty($this->items)) {
            foreach (Company::$listType as $controller => $title) {
                $this->items[$controller] = [
                    'title'  => $title,
                    'class'  => 'empty',
                    'action' => 'list',
                    'role'   => User::ADMIN_ROLE
                ];
            }

            $this->items = array_merge($this->items, [
                'user' => array(
                    'title'  => 'Пользователи',
                    'class'  => 'empty',
                    'action' => Company::COMPANY_TYPE,
                    'role'   => User::ADMIN_ROLE
                ),
                'card' => array(
                    'title'  => 'Карты',
                    'class'  => 'empty',
                    'action' => 'list',
                    'role'   => User::CLIENT_ROLE
                ),
                'type' => array(
                    'title'  => 'Виды и марки ТС',
                    'class'  => 'empty',
                    'action' => 'list',
                    'role'   => User::ADMIN_ROLE
                ),
                'image' => array(
                    'title'   => 'Типы ТС',
                    'class'   => 'empty',
                    'action'  => 'list',
                    'role'    => User::PARTNER_ROLE,
                    'visible' => Yii::app()->user->checkAccess(User::ADMIN_ROLE) || Yii::app()->user->model->company->type != Company::COMPANY_TYPE,
                ),
                'car' => array(
                    'title'  => 'История машин',
                    'class'  => 'empty',
                    'action' => 'list',
                    'role'   => User::CLIENT_ROLE,
                    'visible' => Yii::app()->user->checkAccess(User::ADMIN_ROLE) || Yii::app()->user->model->company->type == Company::COMPANY_TYPE,
                ),
                'carCount' => array(
                    'title'  => 'Кол-во ТС',
                    'class'  => 'empty',
                    'action' => 'list',
                    'role'   => User::CLIENT_ROLE
                ),
                'stat' => array(
                    'title'  => Yii::app()->user->role == User::ADMIN_ROLE ? 'Статистика партнеров' : 'Доходы',
                    'class'  => 'empty',
                    'action' => 'index',
                    'params' => ['type' => Yii::app()->user->checkAccess(User::CLIENT_ROLE) || Yii::app()->user->model->company->type == Company::UNIVERSAL_TYPE ? Company::CARWASH_TYPE : Yii::app()->user->model->company->type],
                    'role'   => User::PARTNER_ROLE,
                ),
                'statCompany' => array(
                    'title'  => Yii::app()->user->role == User::ADMIN_ROLE ? 'Статистика компаний' : 'Расходы',
                    'class'  => 'empty',
                    'action' => 'index',
                    'params' => ['type' => 'carwash', 'showCompany' => 1],
                    'role'   => User::CLIENT_ROLE,
                ),
                'act' => array(
                    'title'  => Yii::app()->user->checkAccess(User::ADMIN_ROLE) ? 'Акты' : 'Добавить машину',
                    'class'  => 'empty',
                    'action' => Yii::app()->user->checkAccess(User::CLIENT_ROLE) || Yii::app()->user->model->company->type == Company::UNIVERSAL_TYPE ? Company::CARWASH_TYPE : Yii::app()->user->model->company->type,
                    'role'   => User::PARTNER_ROLE,
                ),
                'archive' => array(
                    'title'  => Yii::app()->user->model->role == User::ADMIN_ROLE
                        ? 'Ошибочные акты'
                        : (Yii::app()->user->model->role == User::CLIENT_ROLE ? 'Услуги' : 'Архив'),
                    'class'  => 'empty',
                    'action' => Yii::app()->user->model->role == User::ADMIN_ROLE
                        ? 'error?type=carwash'
                        : (Yii::app()->user->checkAccess(User::CLIENT_ROLE) || Yii::app()->user->model->company->type == Company::UNIVERSAL_TYPE ? Company::CARWASH_TYPE : Yii::app()->user->model->company->type),
                    'role'   => User::GUEST_ROLE,
                    'sufix' => $this->getCountOfErrorActs(),
                ),
            ]);
        }

        return $this->items;
    }

    public function run()
    {
        $this->render('menu/index', array('items' => $this->getItems()));
    }


    private function getCountOfErrorActs()
    {
        return count(Act::model()->find()->withErrors()->findAll());
    }
}
