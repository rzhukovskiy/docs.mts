<?php
/**
 * @var $this CarController
 * @var $model Car
 */

$this->renderPartial('_tabs', array('model'=>$model));
$this->renderPartial('_dirty', array('model'=>$model));