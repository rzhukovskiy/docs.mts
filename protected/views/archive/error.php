<?php
/**
 * @var $this ArchiveController
 * @var $model Act
 */

$this->tabs = array(
    'error' => array('url' => '#', 'name' => 'Ошибочные'),
);
?>

<div class="contenttitle radiusbottom0">
    <h2 class="table"><span>Ошибки</span></h2>
</div>

<?php
$this->renderPartial('_error', array('model' => $model));
