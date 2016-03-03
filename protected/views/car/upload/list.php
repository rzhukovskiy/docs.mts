<?php
/**
 * @var $this CarController
 * @var $model Car
 */

$this->renderPartial('_tabs', array('model'=>$model));
$this->tabs['export'] = ['url' => '#', 'name' => 'Загруженные'];
?>
    <div class="contenttitle radiusbottom0">
        <h2 class="table"><span>Добавленные</span></h2>
    </div>
<?php

$this->renderPartial('_list', array('model'=>$model));