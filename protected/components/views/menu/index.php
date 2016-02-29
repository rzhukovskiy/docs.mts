<?php
/** @var WMenu $this */
?>

<ul>
    <?php
    foreach($this->getItems() as $controller => $params) {
        if(Yii::app()->user->checkAccess($params['role']) && (!isset($params['visible']) || $params['visible'])) {
    ?>
            <li<?php if (Yii::app()->controller->id == $controller) { ?> class="current"<?php } ?>>
                <a href="<?=Yii::app()->createUrl($controller . '/' . $params['action'], isset($params['params']) ? $params['params'] : []); ?>" class="<?=$params['class']?>">
                    <span><em><?=$params['title']?></em></span>
                </a>
                <?php
                if (!empty($params['count'])) {
                    echo "<span class='msgcount'>{$params['count']}</span>";
                }
                ?>
            </li>
    <?php
        }
    }
    ?>
</ul>