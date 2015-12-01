<?php

class WHTMLHead extends CWidget{

    public function init() {

        $clientScript = Yii::app()->getClientScript();
        $clientScript->registerCoreScript('jquery');
            
        $clientScript->registerScriptFile('/js/jquery-ui-1.11.4/jquery-ui.min.js');
        $clientScript->registerScriptFile('/js/general.js');
        $clientScript->registerScriptFile('/js/select2/select2.js');
        $clientScript->registerScriptFile('/js/select2/select2_locale_ru.js');
        $clientScript->registerScriptFile('/js/jquery/datepicker-ru.js');
        $clientScript->registerScriptFile('/js/main.js');

        
        $clientScript->registerCssFile('/css/style.css');
        $clientScript->registerCssFile('/js/jquery-ui-1.11.4/jquery-ui.min.css');
        $clientScript->registerCssFile('/css/style.brightblue.css');
        $clientScript->registerCssFile('/css/plugins/jquery.tablesorter.css');
        $clientScript->registerCssFile('/js/select2/select2.css');
    }

    public function run() {
        $this->render('htmlhead/index');
    }

}
