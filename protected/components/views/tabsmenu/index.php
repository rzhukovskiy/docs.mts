<?php
    /**
     * Created by PhpStorm.
     * User: ruslanzh
     * Date: 05/08/16
     * Time: 16:56
     *
     * @var $this WTabsMenu;
     * @var $items array;
     * @var $active string;
     */
?>
<ul class="maintabmenu">
    <?php
        foreach ( $items as $key => $value ) {
            $canAccess = !isset( $value[ 'role' ] ) || Yii::app()->user->checkAccess( $value[ 'role' ]);
            if ( $canAccess )
                if ( $key == $active )
                    $this->render( 'tabsmenu/_item_active', array( 'key' => $key, 'value' => $value ) );
                else
                    $this->render( 'tabsmenu/_item', array( 'key' => $key, 'value' => $value ) );
        }
    ?>
</ul><!--maintabmenu-->