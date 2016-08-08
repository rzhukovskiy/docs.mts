<?php
    /**
     * Created by PhpStorm.
     * User: ruslanzh
     * Date: 05/08/16
     * Time: 17:16
     *
     * @var $this WTabsMenu;
     * @var $value array;
     * @var $key int;
     */

?>
<li class="current <?php echo isset( $value[ 'class' ] ) ? $value[ 'class' ] : ''; ?>">
    <a href="<?php echo $value[ 'url' ]; ?>">
        <?php echo $value[ 'name' ]; ?>
        <?php if ( !empty( $value[ 'sufix' ] ) ): ?>
            <span class="badge small badge-important"><?= $value[ 'sufix' ] ?></span>
        <?php endif; ?>
    </a>
</li>
