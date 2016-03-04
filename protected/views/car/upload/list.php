<?php
/**
 * @var $this CarController
 * @var $firstId int
 */

$this->renderPartial('_tabs');

echo CHtml::link('Назад', Yii::app()->createUrl('car/upload'), array('class' => 'btn-info pull-right', 'style' => 'margin: 0 0 0 20px'));
echo "<br /><br />"
?>
    <div class="contenttitle radiusbottom0">
        <h2 class="table"><span>Добавленные</span></h2>
    </div>
<?php
if ($firstId > 0) {
    $this->renderPartial('upload/_list', ['firstId' => $firstId]);
} else {
    echo "Ничего не импортировано";
}