<?php

class WHTMLHead extends CWidget{

    public function init() {

        $clientScript = Yii::app()->getClientScript();
        $clientScript->registerCoreScript('jquery');
            
        $clientScript->registerScriptFile('/js/jquery-ui/jquery-ui.min.js');
        $clientScript->registerScriptFile('/js/general.js');
        $clientScript->registerScriptFile('/js/select2/select2.js');
        $clientScript->registerScriptFile('/js/select2/select2_locale_ru.js');
        $clientScript->registerScriptFile('/js/jquery/datepicker-ru.js');
        $clientScript->registerScriptFile('/js/main.js');
        $clientScript->registerScriptFile('/js/numeral.min.js');
        $clientScript->registerScriptFile('/js/jquery/jquery.canvasjs.min.js');

        
        $clientScript->registerCssFile('/css/style.css');
        $clientScript->registerCssFile('/js/jquery-ui/jquery-ui.min.css');
        $clientScript->registerCssFile('/css/style.brightblue.css');
        $clientScript->registerCssFile('/css/plugins/jquery.tablesorter.css');
        $clientScript->registerCssFile('/js/select2/select2.css');

        $clientScript->registerScriptFile('/js/wpaint/lib/jquery.ui.core.1.10.3.min.js');
        $clientScript->registerScriptFile('/js/wpaint/lib/jquery.ui.widget.1.10.3.min.js');
        $clientScript->registerScriptFile('/js/wpaint/lib/jquery.ui.mouse.1.10.3.min.js');
        $clientScript->registerScriptFile('/js/wpaint/lib/jquery.ui.draggable.1.10.3.min.js');
        $clientScript->registerScriptFile('/js/wpaint/lib/wColorPicker.min.js');
        $clientScript->registerCssFile('/js/wpaint/lib/wColorPicker.min.css');
        $clientScript->registerCssFile('/js/wpaint/wPaint.min.css');
        $clientScript->registerScriptFile('/js/wpaint/wPaint.min.js');
        $clientScript->registerScriptFile('/js/wpaint/plugins/main/wPaint.menu.main.min.js');
        if (!Yii::app()->user->checkAccess(User::ADMIN_ROLE) && Yii::app()->user->model->company->type != Company::COMPANY_TYPE) {
            $clientScript->registerCssFile('/css/media.css');
        }
    }

    public function run() {
        $this->render('htmlhead/index');
    }

}
