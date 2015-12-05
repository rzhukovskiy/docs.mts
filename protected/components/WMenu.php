<?php

class WMenu extends CWidget
{
    public $msgcount;
    public $creditcount;
    public $ticketcount;
    public $paymentcount;
    public $domaincount;
    private $items;

    public function init()
    {
    }

    //инициализация пунктов меню
    public function getItems()
    {
        if(empty($this->items)) {
            $this->items = array(
                'company' => array(
                    'title'  => 'Компании',
                    'class'  => 'empty',
                    'action' => 'list',
                    'role'   => User::ADMIN_ROLE
                ),
                'card' => array(
                    'title'  => 'Карты',
                    'class'  => 'empty',
                    'action' => 'list',
                    'role'   => User::ADMIN_ROLE
                ),
                'carwash' => array(
                    'title'  => 'Мойки',
                    'class'  => 'empty',
                    'action' => 'list',
                    'role'   => User::ADMIN_ROLE
                ),
                'service' => array(
                    'title'  => 'Сервисы',
                    'class'  => 'empty',
                    'action' => 'list',
                    'role'   => User::ADMIN_ROLE
                ),
                'tires' => array(
                    'title'  => 'Шиномонтаж',
                    'class'  => 'empty',
                    'action' => 'list',
                    'role'   => User::ADMIN_ROLE
                ),
                'user' => array(
                    'title'  => 'Пользователи',
                    'class'  => 'empty',
                    'action' => Company::COMPANY_TYPE,
                    'role'   => User::ADMIN_ROLE
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
                    'role'    => User::MANAGER_ROLE,
                    'visible' => Yii::app()->user->checkAccess(User::ADMIN_ROLE) || Yii::app()->user->model->company->type != Company::COMPANY_TYPE,
                ),
                'car' => array(
                    'title'  => 'История машин',
                    'class'  => 'empty',
                    'action' => 'list',
                    'role'   => User::WATCHER_ROLE,
                    'visible' => Yii::app()->user->checkAccess(User::ADMIN_ROLE) || Yii::app()->user->model->company->type == Company::COMPANY_TYPE,
                ),
                'act' => array(
                    'title'  => 'Акты',
                    'class'  => 'empty',
                    'action' => Yii::app()->user->checkAccess(User::ADMIN_ROLE)  || Yii::app()->user->model->company->type == Company::COMPANY_TYPE ? Company::CARWASH_TYPE : Yii::app()->user->model->company->type,
                    'role'   => User::MANAGER_ROLE,
                ),
                'archive' => array(
                    'title'  => !Yii::app()->user->checkAccess(User::ADMIN_ROLE)&& Yii::app()->user->model->company->type == Company::COMPANY_TYPE ? 'Услуги' : 'Архив',
                    'class'  => 'empty',
                    'action' => Yii::app()->user->checkAccess(User::ADMIN_ROLE) || Yii::app()->user->model->company->type == Company::COMPANY_TYPE ? Company::CARWASH_TYPE : Yii::app()->user->model->company->type,
                    'role'   => User::GUEST_ROLE,
                    'visible' => !Yii::app()->user->checkAccess(User::ADMIN_ROLE),
                ),
            );
        }

        return $this->items;
    }

    public function run()
    {
        $this->render('menu/index');
    }

}
