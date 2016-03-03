<?php

/**
 * DJqueryHighlight Class
 * @author Gia Duy (admin@giaduy.info)
 */
class DJqueryHighlight extends CInputWidget {

    public $scriptUrl;
    public $selector='body';
    public $words = array();

    /**
     * Init widget.
     */
    public function init() {
        if ($this->scriptUrl === null)
            $this->scriptUrl = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . '/assets', false, -1, YII_DEBUG);
    }

    /**
     * Run widget.
     */
    public function run() {
        $this->registerClientScript();
    }

    /**
     * Register CSS and Scripts.
     */
    protected function registerClientScript() {
        $id = $this->id;
        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');
        $cs->registerScriptFile($this->scriptUrl . '/jquery.highlight.js');
        $words=CJSON::encode($this->words);
        $script = '$("'.$this->selector.'").highlight('.$words.');';
        $cs->registerScript(__CLASS__ . '#' . $id, $script, CClientScript::POS_READY);
    }

}
