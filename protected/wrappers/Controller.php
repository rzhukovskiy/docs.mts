<?php

class Controller extends CController
{
    public $layout = 'column';
    public $pageTitle = '';
    public $ajax = array();
    public $breadcrumbs = array();
    public $tabs = array();

    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    public function init()
    {
        CHtml::$afterRequiredLabel = '';
    }

    public function accessRules()
    {
        return array(
            array(
                'allow',
                'actions' => array('login'),
                'users' => array('?'),
            ),
            array('allow',
                'roles' => array(User::ADMIN_ROLE),
            ),
            array('allow',
                'controllers' => ['stat', 'car', 'act', 'home', 'image', 'archive'],
                'roles' => array(User::PARTNER_ROLE),
            ),
            array('allow',
                'controllers' => ['statCompany', 'car', 'home', 'archive', 'actScope'],
                'roles' => array(User::CLIENT_ROLE),
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

}
