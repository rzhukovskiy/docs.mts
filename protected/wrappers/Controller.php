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
                'controllers' => array('car', 'act', 'home', 'image', 'archive'),
                'roles' => array(User::MANAGER_ROLE),
            ),
            array('allow',
                'controllers' => array('car', 'home', 'archive'),
                'roles' => array(User::WATCHER_ROLE),
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

}
