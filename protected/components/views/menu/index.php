<?php
    /**
     * @var WMenu $this
     * @var array $items
     */
?>
<ul>
    <?php foreach ( $items as $controller => $params ) : ?>
        <?php if ( Yii::app()->user->checkAccess( $params[ 'role' ] ) && ( !isset( $params[ 'visible' ] ) || $params[ 'visible' ] ) ) : ?>
            <?php
            $elementClass = ( Yii::app()->controller->id == $controller ) ? 'current' : '';
            $elementUrl = Yii::app()->createUrl( $controller . '/' . $params[ 'action' ], isset( $params[ 'params' ] ) ? $params[ 'params' ] : [ ] );
            ?>
            <li class="<?= $elementClass ?>">
                <a href="<?= $elementUrl ?>" class="<?= $params[ 'class' ] ?>">
                    <span><em><?= $params[ 'title' ] ?></em></span>
                </a>
                <?php
                    if ( !empty( $params[ 'count' ] ) ) :
                        echo "<span class='msgcount'>{$params['count']}</span>";
                    endif;
                ?>
                <?php if ( !empty( $params[ 'sufix' ] ) ) : ?>
                    <span class='msgcount'><?= $params[ 'sufix' ] ?></span>
                <?php endif; ?>
            </li>
        <?php endif; ?>
    <?php endforeach; ?>
</ul>