<?php
/**
 * @var $this CarController
 * @var $model Car
 */

$this->renderPartial('_tabs', array('model'=>$model));

echo CHtml::link('Назад', Yii::app()->createUrl('car/upload'), array('class' => 'btn-info pull-right', 'style' => 'margin: 0 0 0 20px'));
echo "<br /><br />"
?>
    <div class="contenttitle radiusbottom0">
        <h2 class="table"><span>Добавленные</span></h2>
    </div>
<?php
if (count($model) > 0) {
    $this->renderPartial('_list', array('model'=>$model));
} else {
    echo "Ничего не импортировано";
}